<?php

namespace Core;

use Psr\Log\AbstractLogger;
use Psr\Log\InvalidArgumentException;
use Psr\Log\LogLevel;
use ReflectionClass;

class Logger extends AbstractLogger
{
    /**
     * Absolute path to the log file.
     *
     * @var string
     */
    protected $path;

    /**
     * Create a new logger.
     *
     * @param string $path Absolute path to the log file.
     */
    public function __construct(string $path)
    {
        $this->path = $path;
    }

    /**
     * Add a log record at an arbitrary level.
     *
     * @param string $level Log level.
     * @param string $message Log message.
     * @param array $context Context data.
     * @return void
     *
     * @throws \Psr\Log\InvalidArgumentException
     */
    public function log($level, $message, array $context = []): void
    {
        if (!$this->isLevelValid($level)) {
            throw new InvalidArgumentException("Level '$level' is not defined.");
        }

        $record = $this->format($level, (string) $message, $context);
        $this->write($record);
    }

    /**
     * Check if the log level is defined by the PSR-3 specification.
     *
     * @param string $level Log level.
     * @return bool True if defined, false otherwise.
     */
    protected function isLevelValid(string $level): bool
    {
        $reflection = new ReflectionClass(LogLevel::class);
        return in_array($level, $reflection->getConstants());
    }

    /**
     * Prepare a log record.
     *
     * @param string $level Log level.
     * @param string $message Log message.
     * @param array $context Context data.
     * @return string Log record.
     */
    protected function format(string $level, string $message, array $context = []): string
    {
        $format = '[%s] %s: %s' . PHP_EOL;
        return sprintf($format, date('Y-m-d H:i:s'), strtoupper($level), $this->interpolate($message, $context));
    }

    /**
     * Write a log record to the log file.
     *
     * @param string $record Log record.
     * @return void
     */
    protected function write(string $record): void
    {
        $this->createDirectory($this->path);
        file_put_contents($this->path, $record, FILE_APPEND);
    }

    /**
     * Create file directory if it doesn't exist.
     *
     * @param string $path Absolute path to the file.
     * @return void
     */
    protected function createDirectory(string $path): void
    {
        $directory = dirname($path);
        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }
    }

    /**
     * Interpolate context values into the message placeholders.
     *
     * @param string $message Log message.
     * @param array $context Context data.
     * @return string Message with replaced placeholders.
     */
    protected function interpolate(string $message, array $context = []): string
    {
        if (strpos($message, '{') === false || empty($context)) {
            return $message;
        }

        $replace = [];
        foreach ($context as $placeholder => $value) {
            if (is_scalar($value) || (is_object($value) && method_exists($value, '__toString'))) {
                $replace['{' . $placeholder . '}'] = $value;
            }
        }
        return strtr($message, $replace);
    }
}

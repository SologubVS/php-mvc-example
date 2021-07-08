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

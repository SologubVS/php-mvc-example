<?php

namespace Core;

use Psr\Log\AbstractLogger;

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
    }
}

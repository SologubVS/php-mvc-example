<?php

namespace Core;

class Logger
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
}

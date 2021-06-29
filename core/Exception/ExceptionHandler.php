<?php

namespace Core\Exception;

use ErrorException;

class ExceptionHandler
{
    /**
     * Register this instance as an exception handler.
     *
     * @return void
     */
    public function register(): void
    {
        set_error_handler([$this, 'handleError']);
    }

    /**
     * Convert PHP errors to ErrorException instances.
     *
     * @param int $errno Level of the error raised.
     * @param string $errstr Error message.
     * @param string $errfile Filename that the error was raised in.
     * @param int $errline Line number where the error was raised.
     * @return void
     *
     * @throws \ErrorException
     */
    public function handleError(int $errno, string $errstr, string $errfile, int $errline): void
    {
        if (error_reporting() & $errno) {
            throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
        }
    }
}

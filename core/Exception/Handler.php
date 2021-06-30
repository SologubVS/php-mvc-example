<?php

namespace Core\Exception;

use Core\View;
use ErrorException;
use Throwable;

class Handler
{
    /**
     * Register this instance as an exception handler.
     *
     * @return void
     */
    public function register(): void
    {
        set_error_handler([$this, 'handleError']);
        set_exception_handler([$this, 'handleException']);
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

    /**
     * Handle an uncaught exception.
     *
     * @param \Throwable $exception Exception object that was thrown.
     * @return void
     */
    public function handleException(Throwable $exception): void
    {
        $this->report($exception);
        $this->render($exception);
    }

    /**
     * Report an exception to the log.
     *
     * @param \Throwable $exception Exception object to be reported.
     * @return void
     */
    protected function report(Throwable $exception): void
    {
        if (ini_get('log_errors')) {
            error_log("Fatal error: Uncaught $exception");
        }
    }

    /**
     * Render an exception as an HTTP response.
     *
     * @param \Throwable $exception Exception object to be rendered.
     * @return void
     */
    protected function render(Throwable $exception): void
    {
        $this->registerViewPaths();
        View::render('error.html');
    }

    /**
     * Register the error view paths.
     *
     * @return void
     */
    protected function registerViewPaths(): void
    {
        View::addPath(__DIR__ . '/views');
    }
}

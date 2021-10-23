<?php

namespace Core\Exception;

use Core\Entities\ModelNotFoundException;
use Core\Exception\Debug\DebugTrait;
use ErrorException;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;
use Throwable;

class Handler
{
    use DebugTrait;
    use LoggerAwareTrait;

    /**
     * A list of exceptions that are not reported.
     *
     * @var string[]
     */
    protected $notReportable = [
        HttpException::class,
        ModelNotFoundException::class,
    ];

    /**
     * Create a new exception handler.
     *
     * @param \Psr\Log\LoggerInterface $logger Instance of the logger.
     * @param bool $debug Use debug mode.
     */
    public function __construct(LoggerInterface $logger, bool $debug = false)
    {
        $this->setLogger($logger);
        $this->setDebug($debug);
    }

    /**
     * Register this instance as an exception handler.
     *
     * @return void
     */
    public function register(): void
    {
        error_reporting(E_ALL);
        set_error_handler([$this, 'handleError']);
        set_exception_handler([$this, 'handleException']);

        if (!$this->isDebug()) {
            ini_set('display_errors', 'Off');
        }
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
        if ($this->shouldReport($exception)) {
            $this->report($exception);
        }
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
            error_log("PHP Fatal error: Uncaught $exception");
        }
        $this->logger->error($exception);
    }

    /**
     * Render an exception.
     *
     * @param \Throwable $exception Exception object to be rendered.
     * @return void
     */
    protected function render(Throwable $exception): void
    {
        $exception = $this->prepareForRender($exception);
        $renderer = new Renderer($this->shouldDebugRender($exception));
        $renderer->render($exception);
    }

    /**
     * Prepare an exception for rendering.
     *
     * Depending on the type of the exception, returns
     * an exception object of a different type, with the
     * original exception set as the previous.
     *
     * @param \Throwable $exception Exception object that was thrown.
     * @return \Throwable An exception object ready for rendering.
     */
    protected function prepareForRender(Throwable $exception): Throwable
    {
        if ($exception instanceof ModelNotFoundException) {
            return new HttpException(404, $exception->getMessage(), $exception);
        }
        return $exception;
    }

    /**
     * Determine if the exception should be reported.
     *
     * @param \Throwable $exception Exception object to be checked.
     * @return bool True if it should be reported, false otherwise.
     */
    protected function shouldReport(Throwable $exception): bool
    {
        return $this->isDebug() || $this->isReportable($exception);
    }

    /**
     * Determine if debug rendering of an exception is required.
     *
     * @param \Throwable $exception Exception object to be checked.
     * @return bool True if debug rendering is required, false otherwise.
     */
    protected function shouldDebugRender(Throwable $exception): bool
    {
        return $this->isDebug() && !$exception instanceof HttpException;
    }

    /**
     * Determine if the exception is reportable.
     *
     * @param \Throwable $exception Exception object to be checked.
     * @return bool True if exception is reportable, false otherwise.
     */
    protected function isReportable(Throwable $exception): bool
    {
        return empty(array_filter($this->notReportable, function ($type) use ($exception) {
            return $exception instanceof $type;
        }));
    }
}

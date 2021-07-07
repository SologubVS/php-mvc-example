<?php

namespace Core\Exception;

use Core\Exception\Debug\DebugTrait;
use Core\View;
use Throwable;

class Renderer
{
    use DebugTrait;

    /**
     * Create a new exception renderer.
     *
     * @param bool $debug Use debug mode.
     */
    public function __construct(bool $debug = false)
    {
        $this->setDebug($debug);
    }

    /**
     * Render an exception.
     *
     * @param \Throwable $exception Exception object to be rendered.
     * @return void
     */
    public function render(Throwable $exception): void
    {
        $this->registerViewPaths();

        if ($this->isDebug()) {
            View::render('error.trace.html', $this->getDetails($exception));
        } else {
            $code = $this->getStatusCode($exception);
            http_response_code($code);
            View::render($this->getHttpErrorView($code), ['code' => $code]);
        }
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

    /**
     * Get basic info about an exception as an array.
     *
     * @param \Throwable $exception Exception to extract details from.
     * @return array
     */
    protected function getDetails(Throwable $exception): array
    {
        return [
            'exception' => get_class($exception),
            'message'   => $exception->getMessage(),
            'file'      => $exception->getFile(),
            'line'      => $exception->getLine(),
            'trace'     => $exception->getTraceAsString(),
        ];
    }

    /**
     * Get exception status code.
     *
     * Defaults to 500. If the exception is an
     * HttpException instance, its status code is returned.
     *
     * @param \Throwable $exception Exception to get status code for.
     * @return int Status code.
     */
    protected function getStatusCode(Throwable $exception): int
    {
        return $exception instanceof HttpException ? $exception->getStatusCode() : 500;
    }

    /**
     * Get HTTP error view template.
     *
     * @param int $statusCode HTTP status code.
     * @return string Template file name.
     */
    protected function getHttpErrorView(int $statusCode): string
    {
        $template = "$statusCode.html";
        return View::exists($template) ? $template : 'error.http.html';
    }
}

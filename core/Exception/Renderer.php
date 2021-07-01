<?php

namespace Core\Exception;

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
            View::render('error.html', $this->getDetails($exception));
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
}

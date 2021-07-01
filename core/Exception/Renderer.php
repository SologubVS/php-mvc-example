<?php

namespace Core\Exception;

use Core\View;
use Throwable;

class Renderer
{
    /**
     * Debug variable name.
     *
     * @var string
     */
    public const DEBUG_VAR = 'APP_DEBUG';

    /**
     * Debug mode.
     *
     * @var bool
     */
    protected $debug = false;

    /**
     * Create a new exception renderer.
     *
     * If $debug is not specified, the value
     * is taken from the environment variable.
     * @see \Core\Exception\Renderer::DEBUG_VAR
     * @see \Core\Exception\Renderer::isDebugInEnvironment()
     *
     * @param bool $debug Use debug mode.
     */
    public function __construct(?bool $debug = null)
    {
        $this->debug = $debug ?? $this->isDebugInEnvironment();
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

        if ($this->debug) {
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

    /**
     * Check that debug mode is enabled in environment.
     *
     * @return bool True if enabled, false otherwise.
     */
    protected function isDebugInEnvironment(): bool
    {
        return filter_var($_ENV[static::DEBUG_VAR] ?? false, FILTER_VALIDATE_BOOLEAN);
    }
}

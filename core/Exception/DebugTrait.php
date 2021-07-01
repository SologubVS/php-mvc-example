<?php

namespace Core\Exception;

trait DebugTrait
{
    /**
     * Debug environment variable name.
     *
     * @var string
     */
    protected $debugVar = 'APP_DEBUG';

    /**
     * Debug mode state.
     *
     * @var bool
     */
    protected $debug = false;

    /**
     * Set the state of debug mode.
     *
     * If $default is not specified, the
     * value is taken from the environment variable.
     *
     * @param bool|null $default Use debug mode.
     * @return void
     */
    protected function setDebug(?bool $default = null): void
    {
        $this->debug = $default ?? $this->isDebugInEnvironment();
    }

    /**
     * Check that debug mode is enabled.
     *
     * @return bool True if enabled, false otherwise.
     */
    protected function isDebug(): bool
    {
        return $this->debug;
    }

    /**
     * Check that debug mode is enabled in environment.
     *
     * @return bool True if enabled, false otherwise.
     */
    protected function isDebugInEnvironment(): bool
    {
        return filter_var($_ENV[$this->debugVar] ?? false, FILTER_VALIDATE_BOOLEAN);
    }
}

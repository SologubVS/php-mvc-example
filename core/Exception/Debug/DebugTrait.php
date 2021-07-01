<?php

namespace Core\Exception\Debug;

trait DebugTrait
{
    /**
     * Debug mode state.
     *
     * @var bool
     */
    protected $debug = false;

    /**
     * Set the state of debug mode.
     *
     * @param bool $debug Debug mode state.
     * @return void
     */
    protected function setDebug(bool $debug): void
    {
        $this->debug = $debug;
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
}

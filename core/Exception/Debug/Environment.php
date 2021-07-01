<?php

namespace Core\Exception\Debug;

class Environment
{
    /**
     * Debug mode variable name.
     *
     * @var string
     */
    public const DEBUG_VAR = 'APP_DEBUG';

    /**
     * Get debug mode state from environment.
     *
     * @return bool True if debug mode is enabled, false otherwise.
     */
    public static function get(): bool
    {
        return filter_var($_ENV[static::DEBUG_VAR] ?? false, FILTER_VALIDATE_BOOLEAN);
    }
}

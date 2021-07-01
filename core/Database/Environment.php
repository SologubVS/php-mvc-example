<?php

namespace Core\Database;

class Environment
{
    /**
     * Host variable name.
     *
     * @var string
     */
    public const HOST_VAR = 'DB_HOST';

    /**
     * Port variable name.
     *
     * @var string
     */
    public const PORT_VAR = 'DB_PORT';

    /**
     * Database variable name.
     *
     * @var string
     */
    public const DATABASE_VAR = 'DB_DATABASE';

    /**
     * Username variable name.
     *
     * @var string
     */
    public const USERNAME_VAR = 'DB_USERNAME';

    /**
     * Password variable name.
     *
     * @var string
     */
    public const PASSWORD_VAR = 'DB_PASSWORD';

    /**
     * Get database connection configuration from environment.
     *
     * The following associative array elements are returned:
     * host, port, database, username, and password.
     *
     * @return array
     */
    public static function get(): array
    {
        return [
            'host' => ($_ENV[static::HOST_VAR] ?? '') ?: '127.0.0.1',
            'port' => ($_ENV[static::PORT_VAR] ?? '') ?: '3306',
            'database' => $_ENV[static::DATABASE_VAR] ?? '',
            'username' => $_ENV[static::USERNAME_VAR] ?? '',
            'password' => $_ENV[static::PASSWORD_VAR] ?? '',
        ];
    }
}

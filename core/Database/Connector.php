<?php

namespace Core\Database;

use PDO;

class Connector
{
    /**
     * The database connection configuration options.
     *
     * @var array
     */
    protected $config = [];

    /**
     * The default PDO connection options.
     *
     * @var array
     */
    protected $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ];

    /**
     * Create a new connector.
     *
     * The following elements of the configuration associative array are
     * expected: host, port (optional), database, username, and password.
     *
     * @param array $config Connection configuration options.
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * Establish a database connection.
     *
     * @return \PDO New PDO connection instance.
     */
    public function connect(): PDO
    {
        [$username, $password] = [
            $this->config['username'] ?? null,
            $this->config['password'] ?? null,
        ];
        return new PDO($this->getDsn(), $username, $password, $this->options);
    }

    /**
     * Create a DSN string from a configuration.
     *
     * @return string DSN string.
     */
    protected function getDsn(): string
    {
        [$host, $port, $database] = [
            $this->config['host'] ?? null,
            $this->config['port'] ?? null,
            $this->config['database'] ?? null,
        ];
        return isset($port)
            ? "mysql:host={$host};dbname={$database};port={$port}"
            : "mysql:host={$host};dbname={$database}";
    }
}

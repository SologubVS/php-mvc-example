<?php

namespace Core\Entities;

use Core\Database\Connector;
use Core\Database\Environment;
use PDO;

abstract class AbstractModel implements ModelInterface
{
    /**
     * The active PDO connection.
     *
     * @var \PDO
     */
    protected static $pdo;

    /**
     * Get the current PDO connection.
     *
     * @return \PDO The active PDO connection.
     */
    protected static function getPdo(): PDO
    {
        if (static::$pdo === null) {
            static::$pdo = static::createPdo();
        }
        return static::$pdo;
    }

    /**
     * Create a new PDO connection.
     *
     * @return \PDO New PDO connection instance.
     */
    protected static function createPdo(): PDO
    {
        $connector = new Connector(Environment::get());
        return $connector->connect();
    }

    /**
     * {@inheritdoc}
     */
    public static function get(string $key, $value): ?array
    {
        return static::all()->where($key, $value)->first();
    }
}

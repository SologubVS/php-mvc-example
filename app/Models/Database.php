<?php

namespace App\Models;

use Core\AbstractModel;
use PDO;

class Database extends AbstractModel
{
    /**
     * Get all databases as an array.
     *
     * @return array
     */
    public static function all(): array
    {
        $pdo = static::getPdo();
        $statement = $pdo->query('SELECT * FROM information_schema.SCHEMATA');
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
}

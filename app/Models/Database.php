<?php

namespace App\Models;

use Core\Entities\AbstractModel;
use PDO;

class Database extends AbstractModel
{
    /**
     * Get all databases as an array.
     *
     * @return array An associative array of databases info.
     */
    public static function all(): array
    {
        $statement = static::getPdo()->query('SELECT * FROM information_schema.SCHEMATA');
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
}

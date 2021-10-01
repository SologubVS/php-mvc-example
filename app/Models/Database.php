<?php

namespace App\Models;

use Core\Entities\AbstractModel;
use Core\Entities\ModelRecords;
use PDO;

class Database extends AbstractModel
{
    /**
     * Get all databases records.
     *
     * @return \Core\Entities\ModelRecords Collection of databases records.
     */
    public static function all(): ModelRecords
    {
        $statement = static::getPdo()->query('SELECT * FROM information_schema.SCHEMATA');
        return new ModelRecords($statement->fetchAll(PDO::FETCH_ASSOC));
    }
}

<?php

namespace App\Models;

use Core\AbstractModel;

class Database extends AbstractModel
{
    /**
     * Get all databases as an array.
     *
     * @return array
     */
    public static function all(): array
    {
        return [];
    }
}

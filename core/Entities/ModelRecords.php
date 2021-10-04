<?php

namespace Core\Entities;

use Core\Support\Collection;

class ModelRecords extends Collection implements ModelRecordsInterface
{
    /**
     * {@inheritdoc}
     */
    public function where(string $column, $value)
    {
        return $this->filter(function ($record) use ($column, $value) {
            return $record[$column] == $value;
        });
    }
}

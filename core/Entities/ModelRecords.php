<?php

namespace Core\Entities;

use Core\Support\Collection;

class ModelRecords extends Collection implements ModelRecordsInterface
{
    /**
     * Create a new model records collection.
     *
     * @param array[] $array An array of arrays to create the collection.
     */
    public function __construct(array $array = [])
    {
        parent::__construct($array);
    }

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

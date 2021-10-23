<?php

namespace Core\Entities;

use Core\Support\CollectionInterface;

interface ModelRecordsInterface extends CollectionInterface
{
    /**
     * Filter records by the given column value pair.
     *
     * Uses equal comparison, not identical.
     *
     * @param string $column Column for filtering.
     * @param mixed $value Column value.
     * @return static An instance of the filtered collection of records.
     */
    public function where(string $column, $value);
}

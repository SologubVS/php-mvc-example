<?php

namespace Core\Entities;

interface ModelInterface
{
    /**
     * Get all model records.
     *
     * @return \Core\Entities\ModelRecordsInterface Collection of model records.
     */
    public static function all(): ModelRecordsInterface;

    /**
     * Get a model record by its unique key.
     *
     * @param string $key Unique record key.
     * @param string|int $value Key value.
     * @return array|null Record if found, null otherwise.
     */
    public static function get(string $key, $value): ?array;
}

<?php

namespace Core\Entities;

interface ModelInterface
{
    /**
     * Get all records as an array.
     *
     * @return array An associative array of model records.
     */
    public static function all(): array;
}

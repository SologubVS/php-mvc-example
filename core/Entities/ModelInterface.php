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
}

<?php

namespace Core;

use ArrayAccess;
use Countable;
use IteratorAggregate;

interface CollectionInterface extends ArrayAccess, IteratorAggregate, Countable
{
    /**
     * Filter the collection using the specified callback.
     *
     * @param callable $callback The callback function to use.
     * @return static A new instance of the filtered collection.
     */
    public function filter(callable $callback);

    /**
     * Get the value of the first element in the collection.
     *
     * @return mixed
     */
    public function first();

    /**
     * Get the value of the last element in the collection.
     *
     * @return mixed
     */
    public function last();
}

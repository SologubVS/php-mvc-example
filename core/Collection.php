<?php

namespace Core;

use ArrayObject;

class Collection extends ArrayObject implements CollectionInterface
{
    /**
     * Create a new collection.
     *
     * @param array $array An array to create the collection.
     */
    public function __construct(array $array = [])
    {
        parent::__construct($array);
    }

    /**
     * {@inheritdoc}
     */
    public function filter(callable $callback)
    {
        return new static(array_filter((array) $this, $callback));
    }

    /**
     * {@inheritdoc}
     */
    public function first()
    {
        $storage = (array) $this;
        return array_shift($storage);
    }

    /**
     * {@inheritdoc}
     */
    public function last()
    {
        $storage = (array) $this;
        return array_pop($storage);
    }
}

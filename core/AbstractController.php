<?php

namespace Core;

abstract class AbstractController
{
    /**
     * Parameters from the matched route.
     *
     * @var array
     */
    protected $routeParams = [];

    /**
     * Create a new controller.
     *
     * @param array $routeParams Parameters from the matched route.
     */
    public function __construct(array $routeParams)
    {
        $this->routeParams = $routeParams;
    }
}

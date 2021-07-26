<?php

namespace Core\Routing;

interface ControllerInterface
{
    /**
     * Call action method.
     *
     * @param string $action Action method name.
     * @return void
     *
     * @throws \Core\Exception\HttpException
     */
    public function callAction(string $action): void;

    /**
     * Set parameters from the matched route and request URL.
     *
     * @param array $route Route parameters.
     * @param array $query Query parameters.
     * @return void
     */
    public function setParameters(array $route, array $query): void;
}

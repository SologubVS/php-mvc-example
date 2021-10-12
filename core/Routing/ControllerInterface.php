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
     * Set matched route.
     *
     * @param \Core\Routing\RouteInterface $route Matched route.
     * @return void
     */
    public function setRoute(RouteInterface $route): void;

    /**
     * Set query parameters from the request URL.
     *
     * @param array $params Query parameters.
     * @return void
     */
    public function setQueryParameters(array $params): void;
}

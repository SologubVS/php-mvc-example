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
     * Set parameters from the matched route.
     *
     * @param array $params Route parameters.
     * @return void
     */
    public function setRouteParameters(array $params): void;

    /**
     * Set query parameters from the request URL.
     *
     * @param array $params Query parameters.
     * @return void
     */
    public function setQueryParameters(array $params): void;
}

<?php

namespace Core\Routing;

interface RouteInterface
{
    /**
     * Get route path.
     *
     * @return string The route path.
     */
    public function getPath(): string;
}

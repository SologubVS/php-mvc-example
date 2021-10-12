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

    /**
     * Get route parameter by name.
     *
     * @param string $key Parameter name.
     * @param mixed $default Default value if parameter does not exist.
     * @return mixed Parameter value.
     */
    public function getParam(string $key, $default = null);
}

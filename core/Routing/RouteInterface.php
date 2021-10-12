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
     * Get controller class name.
     *
     * @return string Controller class name.
     */
    public function getController(): string;

    /**
     * Get controller action name.
     *
     * @return string Controller action name.
     */
    public function getAction(): string;

    /**
     * Get route parameters.
     *
     * @return array Route parameters.
     */
    public function getParams(): array;

    /**
     * Get route parameter by name.
     *
     * @param string $key Parameter name.
     * @param mixed $default Default value if parameter does not exist.
     * @return mixed Parameter value.
     */
    public function getParam(string $key, $default = null);
}

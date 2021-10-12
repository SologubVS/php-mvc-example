<?php

namespace Core\Routing;

class Route implements RouteInterface
{
    /**
     * The route path.
     *
     * @var string
     */
    protected $path = '';

    /**
     * Route parameters.
     *
     * @var array
     */
    protected $params = [];

    /**
     * Create a new route.
     *
     * @param string $path The route path.
     * @param \Core\Routing\RouteParamsInterface $params Route parameters.
     * @param string $namespace Controllers namespace.
     */
    public function __construct(string $path, RouteParamsInterface $params, string $namespace = '')
    {
        $this->path = $path;
        $this->params = $params->extract();
    }

    /**
     * {@inheritdoc}
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * {@inheritdoc}
     */
    public function getParam(string $key, $default = null)
    {
        return array_key_exists($key, $this->params) ? $this->params[$key] : $default;
    }
}

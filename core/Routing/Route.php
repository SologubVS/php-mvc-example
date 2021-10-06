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
     * Create a new route.
     *
     * @param string $path The route path.
     * @param \Core\Routing\RouteParamsInterface $params Route parameters.
     * @param string $namespace Controllers namespace.
     */
    public function __construct(string $path, RouteParamsInterface $params, string $namespace = '')
    {
        $this->path = $path;
    }

    /**
     * {@inheritdoc}
     */
    public function getPath(): string
    {
        return $this->path;
    }
}

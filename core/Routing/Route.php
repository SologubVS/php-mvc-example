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
     * Controller class name.
     *
     * @var string
     */
    protected $controller = '';

    /**
     * Controller action name.
     *
     * @var string
     */
    protected $action = '';

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
        $this->parseParams($params, $namespace);
    }

    /**
     * Parse route parameters and set appropriate properties.
     *
     * @param \Core\Routing\RouteParamsInterface $params Route parameters.
     * @param string $namespace Controllers namespace.
     * @return void
     */
    protected function parseParams(RouteParamsInterface $params, string $namespace = ''): void
    {
        [$this->controller, $this->action] = [
            MethodPreparer::prepareController($params->extractController(), $namespace),
            MethodPreparer::prepareAction($params->extractAction()),
        ];
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
    public function getController(): string
    {
        return $this->controller;
    }

    /**
     * {@inheritdoc}
     */
    public function getAction(): string
    {
        return $this->action;
    }

    /**
     * {@inheritdoc}
     */
    public function getParam(string $key, $default = null)
    {
        return array_key_exists($key, $this->params) ? $this->params[$key] : $default;
    }
}

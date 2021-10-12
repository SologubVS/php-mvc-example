<?php

namespace Core\Routing;

use Core\Exception\HttpException;

abstract class AbstractController implements ControllerInterface
{
    /**
     * Action method name suffix.
     *
     * @var string
     */
    public const ACTION_NAME_SUFFIX = 'Action';

    /**
     * Matched route.
     *
     * @var \Core\Routing\RouteInterface
     */
    protected $route;

    /**
     * Parameters from the request URL.
     *
     * @var array
     */
    protected $queryParams = [];

    /**
     * Call action method with before and after filters.
     *
     * @param string $action The name of the action.
     * @return void
     *
     * @throws \Core\Exception\HttpException
     */
    public function callAction(string $action): void
    {
        $action = $this->prepareActionName($action);
        if (!method_exists($this, $action)) {
            throw new HttpException(404, sprintf('Action \'%s\' not found in \'%s\'.', $action, get_class($this)));
        }

        if ($this->before() !== false) {
            $this->{$action}();
            $this->after();
        }
    }

    /**
     * Add action method suffix to action name.
     *
     * @param string $name The name of the action.
     * @return string Action method name with suffix.
     */
    protected function prepareActionName(string $name): string
    {
        $suffix = static::ACTION_NAME_SUFFIX;
        if ($name !== '' && !preg_match("/{$suffix}$/i", $name)) {
            $name .= $suffix;
        }
        return $name;
    }

    /**
     * {@inheritdoc}
     */
    public function setRoute(RouteInterface $route): void
    {
        $this->route = $route;
    }

    /**
     * {@inheritdoc}
     */
    public function setQueryParameters(array $params): void
    {
        $this->queryParams = $params;
    }

    /**
     * Before filter.
     *
     * Called before an action method.
     *
     * @return void|bool If returns false, prevents execution of an action method.
     */
    protected function before()
    {
    }

    /**
     * After filter.
     *
     * Called after an action method.
     *
     * @return void
     */
    protected function after(): void
    {
    }
}

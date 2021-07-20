<?php

namespace Core;

use Core\Exception\HttpException;

abstract class AbstractController
{
    /**
     * Action method name suffix.
     *
     * @var string
     */
    public const ACTION_NAME_SUFFIX = 'Action';

    /**
     * Parameters from the matched route.
     *
     * @var array
     */
    protected $routeParams = [];

    /**
     * Create a new controller.
     *
     * @param array $routeParams Parameters from the matched route.
     */
    public function __construct(array $routeParams)
    {
        $this->routeParams = $routeParams;
    }

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
        $suffix = static::ACTION_NAME_SUFFIX;
        $method = preg_match("/{$suffix}$/i", $action) ? $action : $action . $suffix;

        if (method_exists($this, $method)) {
            if ($this->before() !== false) {
                $this->{$method}();
                $this->after();
            }
        } else {
            throw new HttpException(404, "Method $method (in controller " . get_class($this) . ') not found.');
        }
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

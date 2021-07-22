<?php

namespace Core;

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
     * Parameters from the matched route.
     *
     * @var array
     */
    protected $routeParams = [];

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
        if ($action !== '' && !preg_match("/{$suffix}$/i", $action)) {
            $action .= $suffix;
        }

        if (!method_exists($this, $action)) {
            throw new HttpException(404, sprintf('Action \'%s\' not found in \'%s\'.', $action, get_class($this)));
        }

        if ($this->before() !== false) {
            $this->{$action}();
            $this->after();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setParameters(array $params): void
    {
        $this->routeParams = $params;
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

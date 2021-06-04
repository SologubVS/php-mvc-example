<?php

namespace Core;

abstract class AbstractController
{
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
     * Call action methods with before and after filters.
     *
     * @param string $name The name of the method.
     * @param array $arguments Parameters passed to the method.
     * @return mixed
     */
    public function __call(string $name, array $arguments)
    {
        $actionMethod = $name . 'Action';
        if (method_exists($this, $actionMethod)) {
            if ($this->before() !== false) {
                call_user_func_array([$this, $actionMethod], $arguments);
                $this->after();
            }
        } else {
            echo "Method $actionMethod (in controller " . get_class($this) . ') not found.';
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

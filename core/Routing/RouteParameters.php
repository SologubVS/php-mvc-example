<?php

namespace Core\Routing;

class RouteParameters
{
    /**
     * Controller parameter name.
     *
     * Used to extract the controller from route parameters.
     *
     * @var string
     */
    public const CONTROLLER = 'controller';

    /**
     * Controller action parameter name.
     *
     * Used to extract controller action from route parameters.
     *
     * @var string
     */
    public const ACTION = 'action';

    /**
     * Prepare route parameters.
     *
     * Find and process parameter values for controller and action names.
     * @see \Core\Routing\RouteParameters::CONTROLLER
     * @see \Core\Routing\RouteParameters::ACTION
     *
     * @param array $params Route parameters.
     * @param string $namespace Controllers namespace.
     * @return array Route parameters with processed controller and action names.
     */
    public static function prepare(array $params, string $namespace = ''): array
    {
        if (isset($params[static::CONTROLLER])) {
            $params[static::CONTROLLER] = static::prepareController($params[static::CONTROLLER], $namespace);
        }
        if (isset($params[static::ACTION])) {
            $params[static::ACTION] = static::prepareAction($params[static::ACTION]);
        }
        return $params;
    }

    /**
     * Prepare controller parameter value.
     *
     * Only relative controller name will be prefixed with namespace.
     *
     * @param string $controller Controller parameter value.
     * @param string $namespace Controllers namespace.
     * @return string Modified controller parameter value.
     */
    protected static function prepareController(string $controller, string $namespace = ''): string
    {
        if ($controller !== '') {
            $controller = ucfirst(static::prepareParameter($controller));
            $controller = static::addNamespace($namespace, $controller);
        }
        return trim($controller, '\\');
    }

    /**
     * Prepare action parameter value.
     *
     * @param string $action Action parameter value.
     * @return string Modified action parameter value.
     */
    protected static function prepareAction(string $action): string
    {
        return lcfirst(static::prepareParameter($action));
    }

    /**
     * Prepare parameter value.
     *
     * Capitalize each word in the parameter value using separator.
     *
     * @param string $value Parameter value.
     * @return string Modified parameter value.
     */
    protected static function prepareParameter(string $value): string
    {
        if (strpos($value, '-') !== false) {
            $value = str_replace('-', '', ucwords($value, '-'));
        }
        return $value;
    }

    /**
     * Add namespace to relative class name.
     *
     * @param string $namespace Namespace.
     * @param string $class Relative class name.
     * @return string Class name prefixed with namespace.
     */
    protected static function addNamespace(string $namespace, string $class): string
    {
        if ($namespace !== '' && strpos($class, '\\') !== 0) {
            return rtrim($namespace, '\\') . '\\' . $class;
        }
        return $class;
    }
}

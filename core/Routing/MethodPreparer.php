<?php

namespace Core\Routing;

class MethodPreparer
{
    /**
     * Prepare controller parameter value.
     *
     * Only relative controller name will be prefixed with namespace.
     *
     * @param string $controller Controller parameter value.
     * @param string $namespace Controllers namespace.
     * @return string Modified controller parameter value.
     */
    public static function prepareController(string $controller, string $namespace = ''): string
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
    public static function prepareAction(string $action): string
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

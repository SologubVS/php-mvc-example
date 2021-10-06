<?php

namespace Core\Routing;

interface RouteParamsInterface
{
    /**
     * Get controller parameter value.
     *
     * @return string Controller parameter.
     */
    public function extractController(): string;

    /**
     * Get action parameter value.
     *
     * @return string Action parameter.
     */
    public function extractAction(): string;

    /**
     * Get route parameters excluding controller and action.
     *
     * @return array Route parameters.
     */
    public function extract(): array;
}

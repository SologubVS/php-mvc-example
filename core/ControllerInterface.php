<?php

namespace Core;

interface ControllerInterface
{
    /**
     * Call action method.
     *
     * @param string $action Action method name.
     * @return void
     *
     * @throws \Core\Exception\HttpException
     */
    public function callAction(string $action): void;

    /**
     * Set parameters from the matched route.
     *
     * @param array $params An associative array of parameters.
     * @return void
     */
    public function setParameters(array $params): void;
}

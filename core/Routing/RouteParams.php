<?php

namespace Core\Routing;

use RuntimeException;

class RouteParams implements RouteParamsInterface
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
     * Array of route parameters.
     *
     * @var array
     */
    protected $params = [];

    /**
     * Create a new route parameters representation.
     *
     * @param array $params Route parameters.
     */
    public function __construct(array $params)
    {
        $this->validate($params);
        $this->params = $params;
    }

    /**
     * Validate route parameters.
     *
     * Check the presence of the controller and action
     * among the route parameters or throw an exception.
     *
     * @param array $params Route parameters.
     * @return void
     *
     * @throws \RuntimeException
     */
    protected function validate(array $params): void
    {
        if (!isset($params[static::CONTROLLER])) {
            throw new RuntimeException('Controller not found among route parameters.');
        }
        if (!isset($params[static::ACTION])) {
            throw new RuntimeException('Action not found among route parameters.');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function extractController(): string
    {
        return $this->params[static::CONTROLLER];
    }

    /**
     * {@inheritdoc}
     */
    public function extractAction(): string
    {
        return $this->params[static::ACTION];
    }

    /**
     * {@inheritdoc}
     */
    public function extract(): array
    {
        return array_filter($this->params, function ($key) {
            return !in_array($key, [static::CONTROLLER, static::ACTION]);
        }, ARRAY_FILTER_USE_KEY);
    }
}

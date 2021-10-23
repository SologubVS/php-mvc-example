<?php

namespace Core;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class View
{
    /**
     * Templates environment configuration.
     *
     * @var \Twig\Environment
     */
    protected static $environment;

    /**
     * Template file system loader.
     *
     * @var \Twig\Loader\FilesystemLoader
     */
    protected static $loader;

    /**
     * Render a view template file.
     *
     * @see \Twig\Environment::render()
     *
     * @param string $template Relative path to the template file.
     * @param array $data Data passing into the view.
     * @return void
     */
    public static function render(string $template, array $data = []): void
    {
        echo static::getEnvironment()->render($template, $data);
    }

    /**
     * Register a global template variable.
     *
     * @see \Twig\Environment::addGlobal()
     *
     * @param string $name Variable name.
     * @param mixed $value The value of the variable.
     * @return void
     */
    public static function addGlobal(string $name, $value): void
    {
        static::getEnvironment()->addGlobal($name, $value);
    }

    /**
     * Add absolute path where to look for template files.
     *
     * @see \Twig\Loader\FilesystemLoader::addPath()
     *
     * @param string $path Absolute path to templates.
     * @return void
     */
    public static function addPath(string $path): void
    {
        static::getLoader()->addPath($path);
    }

    /**
     * Check if the template file exists at the specified path.
     *
     * @see \Twig\Loader\FilesystemLoader::exists()
     *
     * @param string $template Relative path to the template file.
     * @return bool True if the template file exists, false otherwise.
     */
    public static function exists(string $template): bool
    {
        return static::getLoader()->exists($template);
    }

    /**
     * Get templates environment configuration instance.
     *
     * Create a new instance of the environment if it
     * has not been instantiated yet.
     *
     * @return \Twig\Environment Templates environment configuration.
     */
    protected static function getEnvironment(): Environment
    {
        if (static::$environment === null) {
            static::$environment = new Environment(static::getLoader());
        }
        return static::$environment;
    }

    /**
     * Get template file system loader instance.
     *
     * Create a new instance of the loader if it
     * has not been instantiated yet.
     *
     * @return \Twig\Loader\FilesystemLoader Template file system loader.
     */
    protected static function getLoader(): FilesystemLoader
    {
        if (static::$loader === null) {
            static::$loader = new FilesystemLoader();
        }
        return static::$loader;
    }
}

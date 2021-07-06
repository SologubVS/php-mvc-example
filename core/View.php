<?php

namespace Core;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class View
{
    /**
     * Template file system loader.
     *
     * @var \Twig\Loader\FilesystemLoader
     */
    protected static $loader;

    /**
     * Render a view template file.
     *
     * @param string $template Relative path to the template file.
     * @param array $data Data passing into the view.
     * @return void
     */
    public static function render(string $template, array $data = []): void
    {
        $twig = new Environment(static::getLoader());
        echo $twig->render($template, $data);
    }

    /**
     * Add absolute path where to look for template files.
     *
     * @param string $path Absolute path to templates.
     * @return void
     */
    public static function addPath(string $path): void
    {
        static::getLoader()->addPath($path);
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

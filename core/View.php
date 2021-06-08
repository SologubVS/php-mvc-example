<?php

namespace Core;

class View
{
    /**
     * Absolute paths where to look for template files.
     *
     * @var array
     */
    protected static $paths;

    /**
     * Render a view template file.
     *
     * If the absolute paths to the template files is not
     * set, then search location defaults to getcwd().
     * @see \Core\View::$path
     * @see \Core\View::setPath()
     *
     * @param string $template Relative path to the template file.
     * @param array $data Data passing into the view.
     * @return void
     */
    public static function render(string $template, array $data = []): void
    {
        $paths = static::$paths ?? getcwd();
        $twig = new \Twig\Environment(
            new \Twig\Loader\FilesystemLoader($paths),
        );
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
        static::$paths[] = $path;
    }
}

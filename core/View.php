<?php

namespace Core;

class View
{
    /**
     * Absolute path where to look for template files.
     *
     * @var string
     */
    protected static $path;

    /**
     * Render a view template file.
     *
     * If the absolute path to the template files is not
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
        $path = static::$path ?? getcwd();
        $twig = new \Twig\Environment(
            new \Twig\Loader\FilesystemLoader($path),
        );
        echo $twig->render($template, $data);
    }

    /**
     * Set absolute path where to look for template files.
     *
     * @param string $path Absolute path to templates.
     * @return void
     */
    public static function setPath(string $path): void
    {
        static::$path = $path;
    }
}

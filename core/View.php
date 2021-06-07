<?php

namespace Core;

class View
{
    /**
     * Render a view template file.
     *
     * @param string $template Relative path to the template file.
     * @param array $data Data passing into the view.
     * @return void
     */
    public static function render(string $template, array $data = []): void
    {
        static $twig;
        if ($twig === null) {
            $basePath = defined('BASE_PATH') ? BASE_PATH : realpath(__DIR__);

            $loader = new \Twig\Loader\FilesystemLoader("$basePath/app/views");
            $twig = new \Twig\Environment($loader);
        }

        echo $twig->render($template, $data);
    }
}

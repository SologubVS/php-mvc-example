<?php

namespace Core;

class View
{
    /**
     * Render a view file.
     *
     * @param string $view Relative path to the view file.
     * @return void
     */
    public static function render(string $view): void
    {
        $basePath = defined('BASE_PATH') ? BASE_PATH : realpath(__DIR__);
        $viewPath = "$basePath/app/views/$view";

        if (is_readable($viewPath)) {
            require $viewPath;
        }
    }
}

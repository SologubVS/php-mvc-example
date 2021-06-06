<?php

namespace Core;

class View
{
    /**
     * Render a view file.
     *
     * @param string $view Relative path to the view file.
     * @param array $data Data passing into the view.
     * @return void
     */
    public static function render(string $view, array $data = []): void
    {
        $basePath = defined('BASE_PATH') ? BASE_PATH : realpath(__DIR__);
        $viewPath = "$basePath/app/views/$view";

        if (is_readable($viewPath)) {
            extract($data, EXTR_SKIP);
            require $viewPath;
        }
    }
}

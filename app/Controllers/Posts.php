<?php

namespace App\Controllers;

use Core\AbstractController;
use Core\View;

class Posts extends AbstractController
{
    /**
     * Show the index page.
     *
     * @return void
     */
    public function indexAction()
    {
        View::render('posts/index.html', [
            'controller' => get_class($this),
        ]);
    }

    /**
     * Show the add new page.
     *
     * @return void
     */
    public function addNewAction()
    {
        echo "Hello from the addNew action in the Posts controller!";
    }
}

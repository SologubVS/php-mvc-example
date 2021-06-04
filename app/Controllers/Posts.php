<?php

namespace App\Controllers;

class Posts extends \Core\AbstractController
{
    /**
     * Show the index page.
     *
     * @return void
     */
    public function indexAction()
    {
        echo "Hello from the index action in the Posts controller!";
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

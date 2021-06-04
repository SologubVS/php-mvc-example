<?php

namespace App\Controllers;

class Home extends \Core\AbstractController
{
    /**
     * Show the index page.
     *
     * @return void
     */
    public function indexAction()
    {
        echo "Hello from the index action in the Home controller!";
    }
}

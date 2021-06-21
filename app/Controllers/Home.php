<?php

namespace App\Controllers;

use Core\AbstractController;
use Core\View;

class Home extends AbstractController
{
    /**
     * Show the index page.
     *
     * @return void
     */
    public function indexAction()
    {
        View::render('home/index.html');
    }
}

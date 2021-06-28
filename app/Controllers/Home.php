<?php

namespace App\Controllers;

use Core\AbstractController;
use Core\View;

class Home extends AbstractController
{
    /**
     * Show home page.
     *
     * @return void
     */
    public function indexAction(): void
    {
        View::render('home/index.html');
    }
}

<?php

namespace App\Controllers;

use App\Models\Post;
use Core\AbstractController;
use Core\View;

class Posts extends AbstractController
{
    /**
     * Show the index page.
     *
     * @return void
     */
    public function indexAction(): void
    {
        View::render('posts/index.html', [
            'posts' => Post::all(),
        ]);
    }
}

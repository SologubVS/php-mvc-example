<?php

namespace App\Controllers;

use App\Models\Post;
use Core\Routing\AbstractController;
use Core\View;

class Posts extends AbstractController
{
    /**
     * Show page with all posts.
     *
     * @return void
     */
    public function indexAction(): void
    {
        View::render('posts/index.html', [
            'postsRoute' => $this->route->getPath(),
            'posts' => Post::all(),
        ]);
    }

    /**
     * Show single post page.
     *
     * @return void
     */
    public function showAction(): void
    {
        View::render('posts/single.html', [
            'post' => Post::getBySlug($this->route->getParam('slug')),
        ]);
    }
}

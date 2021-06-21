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
            'posts' => [
                [
                    'title' => 'Post #1',
                    'content' => 'Cras molestie consequat arcu. Praesent pretium nisl non nec.',
                ],
                [
                    'title' => 'Post #2',
                    'content' => 'Maecenas elementum vel mauris et ornare. Nullam at pharetra.',
                ],
                [
                    'title' => 'Post #3',
                    'content' => 'Quisque iaculis bibendum egestas. Nullam mattis, nisi metus.',
                ],
            ],
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

<?php

namespace App\Models;

use Core\Entities\AbstractModel;

class Post extends AbstractModel
{
    /**
     * Get all posts as an array.
     *
     * @return array An associative array of posts info.
     */
    public static function all(): array
    {
        return [
            [
                'title' => 'Post #1',
                'content' => 'Pellentesque venenatis tempor elementum. Suspendisse pretium in.',
            ],
            [
                'title' => 'Post #2',
                'content' => 'Praesent elementum tellus non lorem feugiat dictum. Ut pharetra.',
            ],
            [
                'title' => 'Post #3',
                'content' => 'Proin varius tellus at euismod porttitor. Quisque sit amet duis.',
            ],
        ];
    }
}

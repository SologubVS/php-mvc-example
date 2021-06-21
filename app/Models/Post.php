<?php

namespace App\Models;

use Core\AbstractModel;

class Post extends AbstractModel
{
    /**
     * Get all posts as an array.
     *
     * @return array
     */
    public static function all(): array
    {
        return [
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
        ];
    }
}

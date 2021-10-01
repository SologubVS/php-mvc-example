<?php

namespace App\Models;

use Core\Entities\AbstractModel;
use Core\Entities\ModelRecords;

class Post extends AbstractModel
{
    /**
     * Get all posts records.
     *
     * @return \Core\Entities\ModelRecords Collection of posts records.
     */
    public static function all(): ModelRecords
    {
        return new ModelRecords([
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
        ]);
    }
}

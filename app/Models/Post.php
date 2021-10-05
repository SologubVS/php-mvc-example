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
                'slug' => 'post-1',
                'title' => 'Post #1',
                'content' => 'Pellentesque venenatis tempor elementum. Suspendisse pretium in.',
            ],
            [
                'slug' => 'post-2',
                'title' => 'Post #2',
                'content' => 'Praesent elementum tellus non lorem feugiat dictum. Ut pharetra.',
            ],
            [
                'slug' => 'post-3',
                'title' => 'Post #3',
                'content' => 'Proin varius tellus at euismod porttitor. Quisque sit amet duis.',
            ],
        ]);
    }

    /**
     * Get a post record by its slug or throw an exception.
     *
     * @see \Core\Entities\AbstractModel::getOrFail()
     *
     * @param string $slug Post slug.
     * @return array Post record.
     */
    public static function getBySlug(string $slug): array
    {
        return static::getOrFail('slug', $slug);
    }
}

<?php

namespace App\Controllers;

use App\Models\Database;
use Core\AbstractController;
use Core\View;

class Databases extends AbstractController
{
    /**
     * Show page with all databases.
     *
     * @return void
     */
    public function indexAction(): void
    {
        View::render('databases/index.html', [
            'databases' => Database::all(),
        ]);
    }
}

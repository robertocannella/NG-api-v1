<?php

declare(strict_types=1);

namespace App\Controllers;

use Framework\Viewer;

class Home {

    public function __construct(private Viewer $viewer)
    {
    }

    public function index(): void
    {
        $this->viewer->render("Shared/header.php",["title" => "Home"]);
        $this->viewer->render('Home/index.php');
    }
}
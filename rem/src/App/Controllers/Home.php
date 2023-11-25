<?php

declare(strict_types=1);

namespace App\Controllers;

use Framework\Controller;


class Home extends Controller {

    public function __construct()
    {
    }

    public function index(): void
    {
        $this->viewer->render("Shared/header.php",["title" => "Home"]);
        $this->viewer->render('Home/index.php');
    }
}
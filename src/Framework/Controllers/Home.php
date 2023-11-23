<?php

namespace Framework\Controllers;

class Home {
    public function index(): void
    {
        require 'views/home_index.php';
    }
}
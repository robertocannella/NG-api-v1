<?php

namespace Framework\Controllers;

use Framework\Models\Model;

class Controller

{
    public function __construct(private readonly Model $model)
    {
    }

    public function index(): void
    {

        $products = $this->model->getData();

        require "views/product_index.php";
    }
}
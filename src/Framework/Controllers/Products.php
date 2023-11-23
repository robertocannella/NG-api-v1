<?php

namespace Framework\Controllers;

use Framework\Models\Product;

class Products

{
    public function __construct(private readonly Product $model)
    {
    }

    public function index(): void
    {

        $products = $this->model->getData();

        require "views/product_index.php";
    }
    public function show()
    {
        require "views/product_show.php";
    }
}
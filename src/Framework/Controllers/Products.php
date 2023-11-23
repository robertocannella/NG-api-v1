<?php

namespace Framework\Controllers;

use Framework\Models\Product;


class Products

{
    private Product $product_model;
    public function __construct()
    {
        $this->product_model = new Product();
    }

    public function index(): void
    {

        $products = $this->product_model->getData();

        require "views/product_index.php";
    }
    public function show()
    {
        require "views/product_show.php";
    }
}
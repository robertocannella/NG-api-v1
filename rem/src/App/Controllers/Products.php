<?php

namespace App\Controllers;

use App\Models\Product;
use Framework\Viewer;

class Products

{
    public function __construct(private Viewer $viewer, private Product $product_model)
    {
    }

    public function index(): void
    {
        $products = $this->product_model->getData();

        // pass data as an associative array
        $this->viewer->render('Products/index.php', ["products" => $products]);

    }
    public function show(string $id):void
    {

        $this->viewer->render('Shared/header.php', ["title" => "Products"]);

        // pass data as an associative array
        $this->viewer->render("Products/show.php", ["id" => $id]);

    }
    public function showPage (string $title, string $id, string $page){

        echo $title . " " . $id . " " . $page;
    }
}
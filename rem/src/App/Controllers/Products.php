<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\Product;
use Framework\Exceptions\PageNotFoundException;
use Framework\Viewer;

class Products

{
    public function __construct(private readonly Viewer $viewer, private readonly Product $product_model)
    {
    }

    public function index(): void
    {
        $products = $this->product_model->findAll();

        // pass data as an associative array
        $this->viewer->render('Products/index.php', ["products" => $products]);

    }
    public function show(string $id):void
    {

        $product = $this->product_model->find($id);

        if (! $product ){

            throw new PageNotFoundException();

        }

        $this->viewer->render('Shared/header.php', ["title" => "Products"]);

        // pass data as an associative array
        $this->viewer->render("Products/show.php", ["id" => $id, "product" => $product]);

    }
    public function showPage (string $title, string $id, string $page): void
    {

        echo $title . " " . $id . " " . $page;
    }
}
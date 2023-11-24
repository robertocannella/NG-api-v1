<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\Product;
use Cassandra\Numeric;
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


        $this->viewer->render('Shared/header.php', ["title" => "All Products"]);

        // pass data as an associative array
        $this->viewer->render('Products/index.php', ["products" => $products]);

    }
    public function show(string $id):void
    {

        $product = $this->product_model->find($id);

        if (! $product ){

            throw new PageNotFoundException();

        }

        $this->viewer->render('Shared/header.php', ["title" => "Product Page"]);

        // pass data as an associative array
        $this->viewer->render("Products/show.php", ["id" => $id, "product" => $product]);

    }
    public function edit(string $id):void
    {

        $product = $this->product_model->find($id);

        if (! $product ){

            throw new PageNotFoundException();

        }

        $this->viewer->render('Shared/header.php', ["title" => "Edit Product"]);

        // pass data as an associative array
        $this->viewer->render("Products/edit.php", ["id" => $id, "product" => $product]);

    }
    public function new(): void
    {

        $this->viewer->render('Shared/header.php', ["title" => "New Product"]);

        $this->viewer->render("Products/new.php");
    }
    public function create(): void
    {
        $data = [
            "product_id" => (int) $_POST["product_id"],
            "name" => (string) $_POST["name"],
            "description" => $_POST["description"] ?? null
        ];


        if ($this->product_model->insert($data) > 0) {

            header("Location: /rem/products/{$this->product_model->getInsertId()}/show");
            exit;

        } else {

            $this->viewer->render('Shared/header.php', ["title" => "New Product"]);

            $this->viewer->render("Products/new.php", ["errors" => $this->product_model->getErrors()]);
        }
    }
    public function update(string $id): void
    {

        $product = $this->product_model->find($id);

        if (! $product ){

            throw new PageNotFoundException();

        }

        $data = [
            "product_id" => $product['product_id'], //prevent updating of product_id
            "name" => (string) $_POST["name"],
            "description" => $_POST["description"] ?? null
        ];

        if ($this->product_model->update($id, $data)) {
            header("Location: /rem/products/{$id}/show");
            exit;

        } else {

            $this->viewer->render('Shared/header.php', ["title" => "Edit Product"]);

            $this->viewer->render("Products/edit.php", [
                "errors" => $this->product_model->getErrors(),
                "product" => $product
            ]);
        }

    }
    public function showPage (string $title, string $id, string $page): void
    {

        echo $title . " " . $id . " " . $page;
    }
}
<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\Product;
use Framework\Exceptions\PageNotFoundException;
use Framework\Viewer;
use JetBrains\PhpStorm\NoReturn;


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
        $this->viewer->render('Products/index.php', [
            "products" => $products,
            "total" => $this->product_model->getTotalProducts()
        ]);

    }
    public function getProductById(string $id):array
    {
        $product = $this->product_model->find($id);

        if (! $product ){

            throw new PageNotFoundException();

        }
        return $product;
    }
    public function show(string $id):void
    {

        $product = $this->getProductById($id);

        $this->viewer->render('Shared/header.php', ["title" => "Product Page"]);

        // pass data as an associative array
        $this->viewer->render("Products/show.php", ["id" => $id, "product" => $product]);

    }
    public function edit(string $id):void
    {

        $product = $this->getProductById($id);

        $this->viewer->render('Shared/header.php', ["title" => "Edit Product"]);

        // pass data as an associative array
        $this->viewer->render("Products/edit.php",
            ["id" => $id, "product" => $product, "action" => "edit"]);

    }
    public function new(): void
    {

        $this->viewer->render('Shared/header.php', ["title" => "New Product"]);

        $this->viewer->render("Products/new.php",["action" => "new"]);
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

            $this->viewer->render("Products/new.php", [
                "errors" => $this->product_model->getErrors(),
                "product" => $data,
                "action" => "create"
            ]);
        }
    }
    public function update(string $id): void
    {

        $product = $this->getProductById($id);

        // $product['product_id'] = (int) $_POST["product_id"];; //prevent updating of product_id
        $product["name"]  = (string) $_POST["name"];
        $product["description"] = $_POST["description"] ?? null;


        if ($this->product_model->update($id, $product)) {
            header("Location: /rem/products/{$id}/show");
            exit;

        } else {

            $this->viewer->render('Shared/header.php', ["title" => "Edit Product"]);

            $this->viewer->render("Products/edit.php", [
                "errors" => $this->product_model->getErrors(),
                "product" => $product,
                "action" => "update"
            ]);
        }

    }
    public function delete (string $id):bool{

        $product = $this->getProductById($id);

        $this->viewer->render('Shared/header.php', ["title" => "Delete Product"]);

        $this->viewer->render("Products/delete.php", [
            "product" => $product,
            "action" => "delete"
        ]);

        return false;
    }
    #[NoReturn] public function destroy(string $id):void
    {

        $this->product_model->delete($id);

        header("Location: /rem/products/index");
        exit;

    }
    public function showPage (string $title, string $id, string $page): void
    {

        echo $title . " " . $id . " " . $page;


    }
}
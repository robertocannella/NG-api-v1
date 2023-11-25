<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\Product;
use Framework\Controller;
use Framework\Exceptions\PageNotFoundException;
use JetBrains\PhpStorm\NoReturn;


class Products extends Controller

{

    public function __construct(private readonly Product $product_model)
    {
    }

    public function index(): void
    {
        $products = $this->product_model->findAll();


        // pass data as an associative array
        $this->viewer->render('Products/index.mvc.php', [
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

        // pass data as an associative array
        $this->viewer->render("Products/show.mvc.php", [
            "id" => $id, "product" => $product
        ]);

    }
    public function edit(string $id):void
    {

        $product = $this->getProductById($id);


        // pass data as an associative array
        $this->viewer->render("Products/edit.mvc.php",
            ["id" => $id, "product" => $product, "action" => "edit"]);

    }
    public function new(): void
    {

        $this->viewer->render("Products/new.mvc.php",["action" => "new"]);
    }
    public function create(): void
    {

        $data = [
            "product_id" => (int) $this->request->post["product_id"],
            "name" => (string) $this->request->post["name"],
            "description" => $this->request->post["description"] ?? null
        ];


        if ($this->product_model->insert($data) > 0) {

            header("Location: /rem/products/{$this->product_model->getInsertId()}/show");
            exit;

        } else {

            $this->viewer->render("Products/new.mvc.php", [
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
        $product["name"]  = (string) $this->request->post["name"];
        $product["description"] = $this->request->post["description"] ?? null;


        if ($this->product_model->update($id, $product)) {
            header("Location: /rem/products/{$id}/show");
            exit;

        } else {

            $this->viewer->render("Products/edit.mvc.php", [
                "errors" => $this->product_model->getErrors(),
                "product" => $product,
                "action" => "update"
            ]);
        }

    }
    public function delete (string $id):bool{

        $product = $this->getProductById($id);

        $this->viewer->render('Shared/header.php', ["title" => "Delete Product"]);

        $this->viewer->render("Products/delete.mvc.php", [
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
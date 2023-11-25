<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\Product;
use Framework\Controller;
use Framework\Exceptions\PageNotFoundException;
use Framework\Response;
use JetBrains\PhpStorm\NoReturn;


class Products extends Controller

{

    public function __construct(private readonly Product $product_model)
    {
    }

    public function index(): Response
    {
        $products = $this->product_model->findAll();

        // pass data as an associative array
        return $this->view('Products/index.mvc.php', [
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
    public function show(string $id):Response
    {
        $product = $this->getProductById($id);

        // pass data as an associative array
        return $this->view("Products/show.mvc.php", [
            "id" => $id,
            "product" => $product
        ]);
    }
    public function edit(string $id):Response
    {

        $product = $this->getProductById($id);

        // pass data as an associative array
        return $this->view("Products/edit.mvc.php", [
            "id" => $id,
            "product" => $product,
            "action" => "edit"
            ]);

    }
    public function new(): Response
    {

        return $this->view("Products/new.mvc.php",["action" => "new"]);
    }
    public function create(): Response
    {

        $data = [
            "product_id" => (int) $this->request->post["product_id"],
            "name" => (string) $this->request->post["name"],
            "description" => $this->request->post["description"] ?? null
        ];

        if ($this->product_model->insert($data) > 0) {

            return $this->redirect("/rem/products/{$this->product_model->getInsertId()}/show");

        } else {

           return $this->view("Products/new.mvc.php", [
                "errors" => $this->product_model->getErrors(),
                "product" => $data,
                "action" => "create"
            ]);
        }
    }
    public function update(string $id): Response
    {

        $product = $this->getProductById($id);

        // $product['product_id'] = (int) $_POST["product_id"];; //prevent updating of product_id
        $product["name"]  = (string) $this->request->post["name"];
        $product["description"] = $this->request->post["description"] ?? null;


        if ($this->product_model->update($id, $product)) {

            return $this->redirect("/rem/products/{$id}/show");

        } else {

            return $this->view("Products/edit.mvc.php", [
                "errors" => $this->product_model->getErrors(),
                "product" => $product,
                "action" => "update"
            ]);
        }

    }
    public function delete (string $id): Response
    {

        $product = $this->getProductById($id);

        return $this->view("Products/delete.mvc.php", [
            "product" => $product,
            "action" => "delete"
        ]);

    }
    public function destroy(string $id): Response
    {

        $this->product_model->delete($id);

        return $this->redirect("/rem/products/index");

    }
    public function showPage (string $title, string $id, string $page): void
    {
        echo $title . " " . $id . " " . $page;
    }
    public function responseCodeExample(): Response
    {
        $this->response->setStatusCode(451);

        $this->response->setBody("Unavailable for legal reasons");

        return $this->response;
    }
}
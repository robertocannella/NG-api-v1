<?php

declare(strict_types=1);

namespace App\Controllers;

use Framework\Controller;
use Framework\Response;
use App\Models\User;



class Signup extends Controller
{

    public function __construct(private readonly User $user)
    {
    }

    public function new(): Response
    {
        return $this->view('Signup/new.html.twig');
    }
    /**
     * Sign up a new user
     *
     * @return void
     */
    public function create() : Response
    {

        $data = [
            "name" => (string) $this->request->post["name"],
            "email" => (string) $this->request->post["email"],
            "password" => (string) $this->request->post["password"],
            "password_confirmation" => (string) $this->request->post["password_confirmation"],
            'password_hash' => password_hash($this->request->post["password"], PASSWORD_DEFAULT)
        ];

        $this->user->passwordsMatch($data);

        if ($this->user->insert($data)){

            echo "User added successfully";

        }else{

            return $this->view("Signup/new.html.twig", [ "user" => $data, "errors" => $this->user->getErrors()]);
        }
        return $this->view("Signup/success.html.twig");


    }
}
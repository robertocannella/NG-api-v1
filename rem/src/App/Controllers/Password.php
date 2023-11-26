<?php

declare(strict_types=1);

namespace App\Controllers;

use Framework\Controller;
use Framework\Response;



class Password extends Controller {

    public function reset($token): Response
    {
        return $this->view("Password/index.html.twig", ['token' => $token]);
    }
}
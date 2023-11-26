<?php

declare(strict_types=1);

namespace App\Models;
use Framework\Model;
use JetBrains\PhpStorm\NoReturn;
use \PDO;

class User extends Model
{
    #[Override]
    protected string|null $table = 'product_users';

    #[NoReturn]

    protected function validate (array $data): void
    {

        if (empty($data["name"]) ){

            $this->addError("name", "Name is required");

        }
        if ( ! filter_var( $data["email"], FILTER_VALIDATE_EMAIL) ){

            $this->addError("email", "A valid email is required");
        }

    }

    public function passwordsMatch (&$data): array
    {
        if ( $data["password_confirmation"] !== $data["password"] ){

            $this->addError("password", "Passwords do not match");

        }
        // remove the password from the $data array.
        // The Model class will try to write all elements to database base on array key
        unset($data["password"], $data["password_confirmation"]);

        return $data;
    }
}
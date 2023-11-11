<?php

class TaskController {

    public function __construct(private TaskGateway $gateway)
    {
    }

    public function processRequest(string $method, ?string $id): void{

        if ($id === null){ // no id, route for collections

            if ($method == "GET"){

                echo json_encode($this->gateway->getAll());
            }elseif ($method == "POST"){

                echo "create";
            } else {

                $this->responseMethodNotAllowed("GET, POST");
            }
        } else { // id is present,

            switch ($method) {

                case "GET":
                    echo "show: " . $id;
                    break;
                case "PATCH":
                    echo "update: " . $id;
                    break;
                case "DELETE":
                    echo "delete: " . $id;
                    break;
                default:
                    $this->responseMethodNotAllowed("GET, PATCH, DELETE");
            }
        }
    }
    private function responseMethodNotAllowed(string $allowed_methods):void{

        http_response_code(405);
        header("Allow: ". $allowed_methods);
    }
}
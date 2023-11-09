<?php

class TaskController {

    public function processRequest(string $method, string $id = null): void{

        if ($id === null){ // no id, route for collections

            if ($method == "GET"){

                echo "index";
            }elseif ($method == "POST"){

                echo "create";
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
                    http_response_code(404);
            }
        }
    }
}
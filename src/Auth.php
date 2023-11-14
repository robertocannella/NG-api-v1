<?php

class Auth {

    private int $user_id;
    public function __construct(
        private readonly UserGateway $user_gateway,
        private readonly JWTCodec $codec)
    {
    }

    public function authenticateAPIKey(): bool
    {
        if ( empty($_SERVER['HTTP_X_API_KEY'])){

            http_response_code(400);

            echo json_encode(["message" => "missing api key"]);

            return false;
        }

        $api_key = $_SERVER['HTTP_X_API_KEY'];

        $user = $this->user_gateway->getByAPIKey($api_key);

        if ( $user === false ){

            http_response_code(401);

            echo json_encode(["message" => "Invalid Credentials"]);

            return false;
        }

        $this->user_id = $user["id"];

        return true;
    }

    /**
     * @throws Exception
     */
    public function authenticateAccessToken() :bool
    {
        // TURN "HTTP_AUTHORIZATION" ON IN APACHE or .htaccess. Apache removes it before the header is passed into PHP.
        //      an alternative is to use
        //          $headers = apache_request_headers();
        //          echo $headers["Authorization"];
        if (! preg_match("/^Bearer\s+(.*)$/", $_SERVER["HTTP_AUTHORIZATION"], $matches  ))
        {

            http_response_code(400);
            echo json_encode(["message" => "incomplete authorization header"]);
            return false;
        }

//        $plain_text = base64_decode($matches[1], true);
//
//        if ($plain_text === false) { // failed to base64 decode the string
//
//            http_response_code(400);
//            echo json_encode(["message" => "invalid authorization header"]);
//            return false;
//
//        }
//
//        $data = json_decode($plain_text, true);
//
//        if ($data === null) { // failed to JSON decode the string
//
//            http_response_code(400);
//            echo json_encode(["message" => "invalid JSON"]);
//            return false;
//        }
        try {
            $data = $this->codec->decode($matches[1]);

        }catch (TokenExpiredException){

            http_response_code(401);
            echo json_encode(["message"=>"token expired"]);
            return false;

        } catch (InvalidSignatureException){

            http_response_code(401);
            echo json_encode(["message"=>"invalid signature"]);
            return false;

        } catch (Exception $e){

            http_response_code(400);
            echo json_encode(["message"=>$e->getMessage()]);
            return false;
        }

        $this->user_id = $data["sub"];

        return true;

    }
    public function getUserId():int
    {
        return $this->user_id;
    }
}
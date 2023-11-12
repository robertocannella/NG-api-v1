<?php
// https://datatracker.ietf.org/doc/html/rfc7518

class JWTCodec{

    public function __construct(private readonly string $key)
    {
    }

    public function encode(array $payload): string
    {
        $header = json_encode([
            "typ" => "JWT",
            "alg" => "HS256" // HMAC with SHA-256 algorithm
        ]);

        $header = $this->base64URLEncode($header);

        $payload = json_encode($payload);
        $payload = $this->base64URLEncode($payload);

        $signature = hash_hmac(
            "sha256",
            $header . "." . $payload,
            $this->key,
            true
        );
        $signature = $this->base64URLEncode($signature);

        return $header . "." . $payload . "." . $signature;

    }
    /*
     * decode
     * Description: Use preg_match to extract parts of a JSON Web Token (JWT).
     *    The JWT typically consists of three parts: header, payload, and signature.
     *           The regular expression is used to split the JWT into these parts.
     *              - ^: Match the start of the string.
     *              - (?<header>.+): Capture one or more characters (.) and name it 'header'.
     *              - \.: Match a period (.) to separate the parts.
     *              - (?<payload>.+): Capture one or more characters (.) and name it 'payload'.
     *              - \.: Match another period (.) to separate the parts.
     *              - (?<signature>.+): Capture one or more characters (.) and name it 'signature'.
     *              - $: Match the end of the string.
     *    The resulting $matches array will contain 'header', 'payload', and 'signature'.
     */
    /**
     * @throws InvalidSignatureException
     */
    public function decode(string $token): array
    {

      if (preg_match("/^(?<header>.+)\.(?<payload>.+)\.(?<signature>.+)$/",
              $token,
              $matches) != 1){

          throw new InvalidArgumentException("invalid token format");
      }

      $signature = hash_hmac(
            "sha256",
            $matches["header"] . "." . $matches["payload"],
            $this->key,
            true
      );

      $signature_from_token = $this->base64URLDecode($matches["signature"]);

      if (! hash_equals($signature, $signature_from_token)) {

          throw new InvalidSignatureException("signature does not match");

      }
        return json_decode($this->base64URLDecode($matches["payload"]), true);
    }
    private function base64URLEncode(string $text):string
    {
        return str_replace(
            ["+", "/", "="],
            ["-", "_", ""],
            base64_encode($text)
        );
    }
    private function base64URLDecode(string $text):string
    {
        return base64_decode(str_replace(
            ["-", "_"],
            ["+", "/"],
            $text)
        );
    }
}
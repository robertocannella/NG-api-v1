<?php
namespace Cannella\Sessions;

class Autologin {

    use PersistentProperties;
    private string $token_index;
    private int $lifetimeDays = 30;
    private int $expiry;
    private mixed $secure = true;
    private bool $httponly = true;
    private string $samesite = 'LAX'; // SameSite attribute (can be 'None', 'Lax', or 'Strict')


    public function __construct(
        private readonly \PDO   $db, $token_index = 0,
        private readonly string $cookiePath = '/session',
        private readonly string $domain = 'nuvolagraph.com')
    {
        if ($this->db->getAttribute(\PDO::ATTR_ERRMODE) !== \PDO::ATTR_ERRMODE) {
            $this->db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        }
        $this->token_index = ($token_index <= 31) ? $token_index : 31;
        $this->expiry = time() + ($this->lifetimeDays * 60 * 60 * 24 );
    }
    public function persistentLogin(): void
    {

       if ($_SESSION[$this->sess_ukey] = $this->getUserKey()){

            $this->getExistingData();

            $token = $this->generateToken();

            $this->storeToken($token);

            $this->setCookie($token);

            $_SESSION[$this->sess_persist] = true;

            unset($_SESSION[$this->cookie]);
       }
    }
    public function isStillValidAutologinSession(): bool
    {
        $user_key = $this->getUserKey();
        if(isset($_COOKIE[$this->cookie])){  // If the cookie exists

           $sql = "SELECT $this->sess_ukey FROM $this->table_autologin 
                   WHERE $this->sess_ukey = :key";

           $stmt = $this->db->prepare($sql);
           $stmt->bindParam(':key', $user_key);
           $stmt->execute();

           $sess_key = $stmt->fetchColumn();
           error_log("SESSION KEY: " . $sess_key);
           return $sess_key !== false;
        }
        return false;
    }
    public function checkCredentials(): void
    {

        if(isset($_COOKIE[$this->cookie])){
            $storedToken = $this->parseCookie();

            if ($storedToken == $this->parseCookie()){

                $this->clearOld();

                if ($this->checkCookieToken($storedToken, false)) {
                    $this->cookieLogin($storedToken);
                }

                $newToken = $this->generateToken();
                $this->storeToken($newToken);
                $this->setCookie($newToken);


            } elseif ($this->checkCookieToken($storedToken, true)) {
                $this->deleteAll();
                $_SESSION = [];
                $params = session_get_cookie_params();
                // invalidate the session
                setcookie(session_name(), '', time() - 86400,
                    $params['path'],
                    $params['domain'],
                    $params['secure'],
                    $params['httponly']
                );
                session_destroy();
                // invalidate the auth cookie
                setcookie(
                    $this->cookie, // Name of the cookie
                    '',             // Value of the cookie
                    [
                        'expires' => time() - 86400,    // Expiry time
                        'path' => $this->cookiePath,   // Path where the cookie is available
                        'domain' => $this->domain,     // Domain of the cookie
                        'secure' => $this->secure,     // Whether the cookie should only be transmitted over a secure HTTPS connection
                        'httponly' => $this->httponly, // HttpOnly flag
                        'samesite' => $this->samesite  // SameSite attribute (can be 'None', 'Lax', or 'Strict')
                    ]
                );
            }

        }
    }
    public function logout(bool $all = true): void
    {
        if($all){
            $this->deleteAll();

        }else{
            $token = $this->parseCookie();
            $sql = "UPDATE `$this->table_autologin` SET `$this->col_used` = 1
                    WHERE `$this->col_token` = :token";

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':token', $token);
            $stmt->execute();
        }
        // invalidate the auth cookie
        setcookie(
            $this->cookie, // Name of the cookie
            '',             // Value of the cookie
            [
                'expires' => time() - 86400,    // Expiry time
                'path' => $this->cookiePath,   // Path where the cookie is available
                'domain' => $this->domain,     // Domain of the cookie
                'secure' => $this->secure,     // Whether the cookie should only be transmitted over a secure HTTPS connection
                'httponly' => $this->httponly, // HttpOnly flag
                'samesite' => $this->samesite  // SameSite attribute (can be 'None', 'Lax', or 'Strict')
            ]
        );

    }

    private function getUserKey(){
        $sql = "SELECT `$this->col_ukey` FROM `$this->table_users`
                WHERE `$this->col_name` = :username";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':username', $_SESSION[$this->sess_uname]);
        $stmt->execute();

        return $stmt->fetchColumn();
    }

    private function getExistingData(): void
    {
        $sql = "SELECT `$this->col_data` FROM `$this->table_autologin`
                WHERE `$this->col_ukey` = :key";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam('key', $_SESSION[$this->sess_ukey]);
        $stmt->execute();
        if ($data = $stmt->fetchColumn()){
            session_decode($data);
        }
        $stmt->closeCursor();
    }

    /*
     * Generate a 32 character string (token)
     */
    private function generateToken(): string
    {
        return bin2hex(openssl_random_pseudo_bytes(16));

    }
    private function encryptToken(): string
    {
        // TODO: IMPLEMENT THIS BEFORE SAVING COOKIE IN BROWSER
        return '';
    }
    private function storeToken($token): void
    {

        $sql = "INSERT INTO `$this->table_autologin`
                (`$this->col_ukey`, `$this->col_token`)
                VALUES (:key, :token)";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':key', $_SESSION[$this->sess_ukey]);
            $stmt->bindParam(':token', $token);
            $stmt->execute();
        }catch (\PDOException $e){
            if ($this->db->inTransaction()){
                $this->db->rollBack();
            }
            throw $e;
        }
    }
    private function setCookie($token): void
    {
        $merged = str_split($token);
        array_splice($merged, hexdec($merged[$this->token_index]), 0, $_SESSION[$this->sess_ukey]);
        $merged = implode('', $merged);

        $token = $_SESSION[$this->sess_uname] . '|' . $merged;

        setcookie($this->cookie, $token,
            [
                'expires' => $this->expiry,    // Expiry time
                'path' => $this->cookiePath,   // Path where the cookie is available
                'domain' => $this->domain,     // Domain of the cookie
                'secure' => $this->secure,     // Whether the cookie should only be transmitted over a secure HTTPS connection
                'httponly' => $this->httponly, // HttpOnly flag
                'samesite' => $this->samesite  // SameSite attribute (can be 'None', 'Lax', or 'Strict')
            ]
        );

    }
    /*
     *
     * Reads the token out of the stored single use Cookie
     * TODO: NEEDS TO BE ENCRYPTED
     */
    private function parseCookie(): array|bool|string
    {

        if (isset($_COOKIE[$this->cookie])){
            $parts = explode('|', $_COOKIE[$this->cookie]);
            $_SESSION[$this->sess_uname] = $parts[0];
            $token = $parts[1];

            if ($_SESSION[$this->sess_ukey] = $this->getUserKey()){
                return str_replace($_SESSION[$this->sess_ukey], '', $token);
            }

        }
        return false;
    }
    /*
     *
     * Removes any expired tokens from the database
     *
     */
    private function clearOld(): void
    {
        $sql = "DELETE FROM `$this->table_autologin` 
                WHERE DATE_ADD($this->col_created, INTERVAL :expiry DAY) < NOW()";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':expiry', $this->lifetimeDays);
        $stmt->execute();
    }

    /*
     * Checks if token has been used.
     */
    private function checkCookieToken($storedToken, bool $used): bool
    {
        $sql = "SELECT COUNT(*) FROM `$this->table_autologin`
                WHERE `$this->col_ukey` = :key AND `$this->col_token` = :token 
                AND `$this->col_used` = :used";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':key', $_SESSION[$this->sess_ukey]);
        $stmt->bindParam(':token', $storedToken);
        $stmt->bindParam(':used', $used, \PDO::PARAM_BOOL);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;

    }

    private function cookieLogin($storedToken): void
    {
     try{
         $this->getExistingData(); // $_SESSION[$this->sess_ukey]);

         $sql = "UPDATE `$this->table_autologin` SET `$this->col_used` = 1 
                 WHERE `$this->col_ukey` = :key AND `$this->col_token` = :token";

         $stmt = $this->db->prepare($sql);
         $stmt->bindParam(':key', $_SESSION[$this->sess_ukey]);
         $stmt->bindParam(':token', $storedToken);
         $stmt->execute();

         session_regenerate_id(true);

         $_SESSION[$this->cookie] = true;

         unset($_SESSION[$this->sess_auth]);
         unset($_SESSION[$this->sess_validate]);
         unset($_SESSION[$this->sess_persist]);


     }   catch (\PDOException $e){
         if ($this->db->inTransaction()){
             $this->db->rollBack();
         }
         throw $e;
     }


    }

    private function deleteAll(): void
    {

        $sql[] = "DELETE FROM `$this->table_autologin` WHERE `$this->col_ukey` = :key";
        $sql[] = "DELETE FROM `$this->table_sess` WHERE `$this->col_ukey` = :key";

        foreach ($sql as $query):
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':key', $_SESSION[$this->sess_ukey]);
        $stmt->execute();
        endforeach;

    }


}
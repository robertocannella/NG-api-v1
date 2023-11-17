<?php
use Cannella\Sessions;

class PersistentSessionHandler extends Sessions\MysqlSessionHandler {

    use Sessions\PersistentProperities;

    /**
     * @inheritDoc
     */
    public function write(string $id, string $data): bool
    {
        $sql = "INSERT INTO $this->table_sess (
                    $this->col_sid, $this->col_expiry, $this->col_data)
                    VALUES (:sid, :expiry, :data)
                    ON DUPLICATE KEY UPDATE
                    $this->col_expiry = :expiry_update,
                    $this->col_data = :data_update";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':expiry', $this->expiry, \PDO::PARAM_INT);
            $stmt->bindParam(':data', $data, \PDO::PARAM_STR);
            $stmt->bindParam(':sid', $id);
            $stmt->bindParam(':expiry_update', $this->expiry, \PDO::PARAM_INT);
            $stmt->bindParam(':data_update', $data, \PDO::PARAM_STR);
            $stmt->execute();


            if(isset($_SESSION[$this->sess_persist]) || isset($_SESSION[$this->cookie])){
                $this->storeAutologinData($data);
            }
            return true;

        }catch (\PDOException $e){
            if ($this->db->inTransaction()){
                $this->db->rollBack();
            }
            throw $e;
        }
    }
    private function storeAutologinData($data){

        // get the user key if it's not already strored as a session variable
        if (!isset($_SESSION[$this->sess_ukey])){
            $sql = "SELECT `$this->col_ukey` FROM `$this->table_users`
                    WHERE `$this->col_name` = :username";

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':username', $_SESSION[$this->sess_uname]);
            $stmt->execute();
            $_SESSION[$this->sess_ukey] = $stmt->fetchColumn();

        }
        // copy the session data to the autlogin table
        $sql = "UPDATE `$this->table_autologin` 
                SET `$this->col_data = :data WHERE `$this->col_ukey = :key";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':data', $data);
        $stmt->bindParam(':key', $_SESSION[$this->sess_ukey]);
        $stmt->execute();

    }
}
<?php

namespace Cannella\Sessions;

use PDOException;
use PDOStatement;

class MysqlSessionHandler implements \SessionHandlerInterface
{

    protected int $expiry;
    protected string $table_sess = 'sessions';
    protected string $col_sid = 'sid';
    protected string $col_expiry = 'expiry';
    protected string $col_data = 'data';
    protected array $unlockStatements = [];
    protected bool $collectGarbage = false;


    public function __construct(
        protected \PDO $db,
        protected bool $useTransactions = true)
    {
        if ($this->db->getAttribute(\PDO::ATTR_ERRMODE) !== \PDO::ERRMODE_EXCEPTION){
            $this->db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        }
        $this->expiry = time() + (int) ini_get('session.gc_maxlifetime');

    }

    /**
     * Close the session and writes the session data to the database
     * @inheritDoc
     */
    public function close(): bool
    {
        if ($this->db->inTransaction()){
            $this->db->commit();

        }elseif ($this->unlockStatements){
            while ($unlockStmt = array_shift($this->unlockStatements)){
                $unlockStmt->execute();
            }
        }

        if ($this->collectGarbage){
            $sql = "DELETE FROM $this->table_sess WHERE $this->col_expiry < :time";

            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':time', time(), \PDO::PARAM_INT);
            $stmt->execute();
            $this->collectGarbage = false;
        }
        return true;
    }

    /**
     * @inheritDoc
     */
    public function destroy(string $id): bool
    {
        // TODO: Implement destroy() method.
    }

    /**
     * @inheritDoc
     */
    public function gc(int $max_lifetime): int|false
    {
        $this->collectGarbage = true;
        return true;
    }

    /**
     * @inheritDoc
     */
    public function open(string $path, string $name): bool
    {
        return true;
    }

    /**
     * Reads the session data for a given session ID. If using transactions, it sets the isolation level to
     * 'READ COMMITTED' and locks the session data for update. If not using transactions, it acquires a lock
     * using the getLock method. It then fetches the session data from the database. If the session is expired
     * or not found, an empty string is returned. In the case of a new session, the record is initialized
     * if transactions are used. Exception handling includes rolling back the transaction in case of a failure.
     *
     * @param string $id The session ID for which the data is to be read.
     * @return string|false Returns the session data as a string, or false on failure.
     */

    public function read(string $id): string|false
    {
        try
        {
            if ($this->useTransactions){
                $this->db->exec('SET TRANSACTION ISOLATION LEVEL READ COMMITTED');
                $this->db->beginTransaction();
            } else {
                $this->unlockStatement[] = $this->getLock($id);
             }
            $sql = "SELECT $this->col_expiry, $this->col_data
                   FROM $this->table_sess
                   WHERE $this->col_sid = :sid";

            if ($this->useTransactions){
                $sql .= ' FOR UPDATE';
            }
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':sid', $id,\PDO::PARAM_INT);
            $stmt->execute();
            $results = $stmt->fetch(\PDO::FETCH_ASSOC);
            if ($results){
                if ($results[$this->col_expiry] < time()){

                    return '';
                }
                return $results[$this->col_data];
            }
            // if session has not been created, we reach this section
            // and the session hasn't been registered in the database.
            if ($this->useTransactions){
                $this->initializeRecord($stmt);
            }
            return '';
        }
        catch (PDOException $e)
        {
            if ($this->db->inTransaction()){
                $this->db->rollBack();
            }
            throw $e;

        }
    }

    /**
     * @inheritDoc
     */
    public function write(string $id, string $data): bool
    {
        try {
            $sql = "INSERT INTO $this->table_sess (
                    $this->col_sid, $this->col_expiry, $this->col_data)
                    VALUES (:sid, :expriry, :data)
                    ON DUPLICATE KEY UPDATE
                    $this->col_expiry = :expriy,
                    $this->col_data = :data";

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':expiry', $this->expiry, \PDO::PARAM_INT);
            $stmt->bindParam(':data', $data, \PDO::PARAM_STR);
            $stmt->bindParam(':sid', $id, \PDO::PARAM_INT);
            $stmt->execute();

            return true;

        }catch (\PDOException $e){
            if ($this->db->inTransaction()){
                $this->db->rollBack();
            }
            throw $e;
        }
    }

    /**
     * Prepares and executes a database lock acquisition for a given session ID and returns
     * a prepared statement for releasing the lock. The lock is attempted with a timeout of 50 seconds.
     * The returned statement must be executed later to release the lock.
     *
     * @param string $session_id The session ID for which the lock is to be acquired.
     * @return bool|PDOStatement Prepared statement for releasing the acquired lock.
     */

    protected function getLock(string $session_id): bool|PDOStatement
    {
        $stmt = $this->db->prepare('SELECT GET_LOCK(:key 50');
        $stmt->bindValue(':key',$session_id);
        $stmt->execute();

        $releaseStmt = $this->db->prepare('DO RELEASE_LOCK(:key)');
        $releaseStmt->bindValue(':key', $session_id);

        return $releaseStmt;
    }
    /**
     * Initializes a new session record in the database. It attempts to insert a new row with the session ID,
     * expiry time, and an empty session data string. If a duplicate key error occurs (signified by exception
     * codes starting with '23'), it attempts to fetch and return existing session data using the provided
     * select statement. For other exceptions, it rolls back any ongoing transaction and rethrows the exception.
     *
     * @param PDOStatement $selectStmt A prepared SELECT statement used to fetch existing session data in case of a duplicate key error.
     * @return string Returns session data if a duplicate key error occurs and data exists, or an empty string otherwise.
     * @throws PDOException Propagates any caught PDOExceptions after handling.
     */

    protected function initializeRecord(PDOStatement $selectStmt): string
    {
        try {
            $sql = "INSERT INTO $this->table_sess ($this->col_sid, $this->expiry, $this->coldaata)
                    VALUES (:sid, :expiry,:data)";
            $insertStmt = $this->db->prepare($sql);
            $insertStmt->bindParam(':sid', $session_id);
            $insertStmt->bindParam(':expiry', $this->expiry, \PDO::PARAM_INT);
            $insertStmt->bindValue(':data', '');
            $insertStmt->execute();
            return '';
        }
        catch (PDOException $e)
        {
            if (str_starts_with($e->getCode(), '23')){
                $selectStmt->execute();
                $results = $selectStmt->fetch(\PDO::FETCH_ASSOC);
                if ($results){
                    return $results[$this->col_data];
                }
                return '';
            }

            if ($this->db->inTransaction())
            {
                $this->db->rollBack();
            }
            throw $e;
        }
    }
}
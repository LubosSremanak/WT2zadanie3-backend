<?php

class DatabaseService
{
    private PDO|null $connection;

    /**
     * DatabaseService constructor.
     */
    public function __construct()
    {
    }

    /**
     * @return PDO
     */
    public function getConnection(): PDO
    {
        return $this->connection;
    }

    public function connect(DatabaseConnectionProperties $properties): ?PDO
    {
        $servername = $properties->getServername();
        $username = $properties->getUsername();
        $password = $properties->getPassword();
        $name = $properties->getName();
        try {
            $this->connection = new PDO("mysql:host=$servername;dbname=$name", $username, $password);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
        return $this->connection;
    }

    public function loadData(string $sqlQuery): array
    {

        $sqlData = $this->connection->prepare($sqlQuery);
        $sqlData->execute();
        return $sqlData->fetchAll(PDO::FETCH_ASSOC);
    }

    public function removeData($sqlQuery)
    {
        try {
            $sqlData = $this->connection->prepare($sqlQuery);
            $sqlData->execute();
        } catch
        (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function insertData($sqlPrepare)
    {
        try {
            $sqlPrepare->execute();
        } catch
        (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function disconnect()
    {
        $this->connection = null;
    }
}

<?php
include "config.php";
class DatabaseConnectionProperties
{
    private string $servername;
    private string $username;
    private string $password;
    private string $name;

    /**
     * DatabaseConnectionProperties constructor.
     */
    public function __construct()
    {
        $this->servername = SERVER_NAME;
        $this->username = USERNAME;
        $this->password = PASSWORD;
        $this->name = DB_NAME;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }


    /**
     * @return string
     */
    public function getServername(): string
    {
        return $this->servername;
    }

    /**
     * @param mixed $servername
     */
    public function setServername(string $servername)
    {
        $this->servername = $servername;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername(string $username)
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword(string $password)
    {
        $this->password = $password;
    }

    public function __toString(): string
    {
        return "Database name:" . $this->getName();
    }


}

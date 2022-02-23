<?php
require_once "../database/service/DatabaseService.php";
require_once "../database/model/DatabaseConnectionProperties.php";
require_once "../config.php";
require_once "ChromePhp.php";
require_once "model/User.php";

class CreateTimeStampController
{
    private DatabaseService $databaseService;

    /**
     * CreateTimeStampController constructor.
     */
    public function __construct()
    {
        $this->databaseService = new DatabaseService();
    }


    public function createTimeStamp()
    {
        $email = $this->getEmail();
        $timezone = "Europe/Bratislava";
        date_default_timezone_set($timezone);
        $timestamp = date("Y-m-d H:i:s");
        $sqlQuery = "INSERT INTO access (accountId, timestamp)
        SELECT account.id, '$timestamp'
        FROM user
        INNER JOIN account ON account.userid = user.id
        where email = '$email';";
        $connection = $this->databaseService->connect(new DatabaseConnectionProperties());
        $sqlPrepare = $connection->prepare($sqlQuery);
        $this->databaseService->insertData($sqlPrepare);
        $this->databaseService->disconnect();

    }

    public function getEmail()
    {
        $email = file_get_contents("php://input");
        return json_decode($email)->email;
    }
}

$createTimeStampController = new CreateTimeStampController();
$createTimeStampController->createTimeStamp();

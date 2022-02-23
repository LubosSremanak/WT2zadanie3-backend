<?php

require_once "../database/service/DatabaseService.php";
require_once "../database/model/DatabaseConnectionProperties.php";
require_once "../config.php";
require_once "model/TwoFA.php";
require_once "ChromePhp.php";
require_once "model/User.php";

class LoginStatsController
{
    private DatabaseService $databaseService;

    /**
     * CreateTimeStampController constructor.
     */
    public function __construct()
    {
        $this->databaseService = new DatabaseService();
    }

    public function getUserStats(): bool|string
    {
        $email = $this->getEmail();
        $sqlQuery = "SELECT timestamp
        FROM access
         JOIN account a on a.id = access.accountId
         JOIN user u on u.id = a.userId
        where u.email='$email';";
        $this->databaseService->connect(new DatabaseConnectionProperties());
        $users = $this->databaseService->loadData($sqlQuery);
        $this->databaseService->disconnect();
        return json_encode($users);
    }

    public function getAllStats(): bool|string
    {
        $sqlQuery = "select type, count(type) as count
                     from account
                     group by type;";
        $this->databaseService->connect(new DatabaseConnectionProperties());
        $allStats = $this->databaseService->loadData($sqlQuery);
        $this->databaseService->disconnect();
        return json_encode($allStats);
    }

    public function getEmail()
    {
        $email = file_get_contents("php://input");
        return json_decode($email)->email;
    }

    public function getStats()
    {
        $all = $this->getAllStats();
        $user = $this->getUserStats();
        echo json_encode(array("userStats" => $user, "allStats" => $all));
    }
}

$loginStats = new LoginStatsController();
$loginStats->getStats();

<?php

require_once "../database/service/DatabaseService.php";
require_once "../database/model/DatabaseConnectionProperties.php";
require_once "../config.php";
require_once "ChromePhp.php";


class CheckEmailDuplicityController
{
    private DatabaseService $databaseService;

    /**
     * CheckEmailDuplicityController constructor.
     */
    public function __construct()
    {
        $this->databaseService = new DatabaseService();

    }

    public function checkEmailDuplicity()
    {
        $response = file_get_contents("php://input");
        $email = json_decode($response)->email;
        $this->databaseService->connect(new DatabaseConnectionProperties());
        $duplicity = $this->databaseService->loadData("
        SELECT type FROM user 
        join account ON account.userId = user.id
        WHERE email ='$email'");
        $this->databaseService->disconnect();
        echo json_encode($duplicity);
    }
}

$checkEmailDuplicityController = new CheckEmailDuplicityController();
$checkEmailDuplicityController->checkEmailDuplicity();

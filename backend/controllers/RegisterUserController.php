<?php

require_once "../database/service/DatabaseService.php";
require_once "../database/model/DatabaseConnectionProperties.php";
require_once "../config.php";
require_once "model/TwoFA.php";
require_once "ChromePhp.php";


class RegisterUserController
{

    private DatabaseService $databaseService;

    /**
     * RegisterUserController constructor.
     */
    public function __construct()
    {
        $this->databaseService = new DatabaseService();
    }

    function bindValuesUser($sqlPrepare, $responseData)
    {
        $index = 1;
        foreach ($responseData as $item) {
            if (!$item) {
                $sqlPrepare->bindValue($index, null, PDO::PARAM_NULL);
            } else
                $sqlPrepare->bindValue($index, $item);
            $index++;
        }
        return $sqlPrepare;
    }

    function bindValuesAccount($sqlPrepare, $responseData, $userId)
    {
        $index = 1;
        foreach ($responseData as $item) {
            if (!$item) {
                $sqlPrepare->bindValue($index, null, PDO::PARAM_NULL);
            } else {
                $sqlPrepare->bindValue($index, $item);
            }

            $index++;
        }
        $passwordHash = strval(password_hash($responseData->password, PASSWORD_DEFAULT));
        $sqlPrepare->bindValue(5, $userId);
        $sqlPrepare->bindValue(2, $passwordHash);
        return $sqlPrepare;
    }

    public function createUser()
    {
        $registerResponse = $this->getRegisterResponse();
        $user = $registerResponse->user;


        $sqlQuery = "INSERT INTO user (username,email)
                    VALUES (?,?)";

        $connection = $this->databaseService->connect(new DatabaseConnectionProperties());
        $sqlPrepare = $connection->prepare($sqlQuery);
        $sqlPrepare = $this->bindValuesUser($sqlPrepare, $user);
        $this->databaseService->insertData($sqlPrepare);
        $this->databaseService->disconnect();
        $this->createAccount($connection);
    }

    public function createAccount($connection)
    {
        $userId = intval($connection->lastInsertId());
        $registerResponse = $this->getRegisterResponse();
        $account = $registerResponse->account;

        $sqlQuery = "INSERT INTO account (account.type,password,googleId,secret,userId)
                    VALUES (?,?,?,?,?)";
        $connection = $this->databaseService->connect(new DatabaseConnectionProperties());
        $sqlPrepare = $connection->prepare($sqlQuery);
        $sqlPrepare = $this->bindValuesAccount($sqlPrepare, $account, $userId);
        $this->databaseService->insertData($sqlPrepare);
        $this->databaseService->disconnect();
    }

    public function getRegisterResponse()
    {
        $registerResponse = file_get_contents("php://input");
        return json_decode($registerResponse);
    }
}

$registerUserController = new RegisterUserController();
$registerUserController->createUser();

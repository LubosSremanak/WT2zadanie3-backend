<?php

require_once "../database/service/DatabaseService.php";
require_once "../database/model/DatabaseConnectionProperties.php";
require_once "../config.php";
require_once "model/TwoFA.php";
require_once "ChromePhp.php";
require_once "model/User.php";

class LoginUserController
{
    private DatabaseService $databaseService;


    public function __construct()
    {
        $this->databaseService = new DatabaseService();
    }

    public function getUser()
    {
        $response = $this->getLoginResponse()->login;
        $email = $response->email;
        $password = $response->password;
        $this->databaseService->connect(new DatabaseConnectionProperties());
        $user = $this->databaseService->loadData("
        SELECT username,email,secret,password FROM user 
        join account ON account.userId = user.id
        WHERE email ='$email'");
        $this->databaseService->disconnect();
        $user = json_encode($user);
        $user = json_decode($user)[0];
        $correctPassword = password_verify($password, $user->password);
        if ($correctPassword) {
            echo json_encode(new User($user->username, $user->email, $user->secret));
        }
    }

    public function getLoginResponse()
    {
        $loginResponse = file_get_contents("php://input");
        return json_decode($loginResponse);
    }
}

$loginUserController = new  LoginUserController();
$loginUserController->getUser();

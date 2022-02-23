<?php
require_once "model/TwoFA.php";
require_once "ChromePhp.php";
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, Accept, Authorization, X-Request-With');
header('Access-Control-Allow-Credentials: true');

class TwoFACheckCodeController
{
    public function __construct($registerResponse)
    {
        $twoFA = new TwoFA();
        echo json_encode($twoFA->checkCode($registerResponse));
    }

}

$registerResponse = file_get_contents("php://input");
$registerResponse = json_decode($registerResponse);
$twoFACheckCode = new TwoFACheckCodeController($registerResponse);

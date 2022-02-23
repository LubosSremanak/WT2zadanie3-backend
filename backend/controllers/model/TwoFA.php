<?php

use JetBrains\PhpStorm\Pure;

require_once 'PHPGangsta/GoogleAuthenticator.php';
require_once 'TwoFAData.php';

class TwoFA
{
    private PHPGangsta_GoogleAuthenticator $ga;

    /**
     * TwoFAController constructor.
     */
    #[Pure] public function __construct()
    {
        $this->ga = new PHPGangsta_GoogleAuthenticator();
    }

    private function extractCode(string $qrCodeUrl): string
    {

        $codeVariables = explode("%", $qrCodeUrl);
        $lastVariableIndex = count($codeVariables) - 1;
        return explode("&", $codeVariables[$lastVariableIndex])[0];
    }

    public function generateCode()
    {
        try {
            $secret = $this->ga->createSecret();
            $qrCodeUrl = $this->ga->getQRCodeGoogleUrl('SremanakAuthCode', $secret);
            $codeFromUrl = $this->extractCode($qrCodeUrl);
            $parts = parse_url($qrCodeUrl);
            parse_str($parts['query'], $query);
            $data = new TwoFAData(substr($codeFromUrl, 2), $query['data'], $secret);
            echo json_encode($data);
        } catch (Exception $e) {
            echo $e;
        }
    }

    public function checkCode($registerResponse): bool
    {

        return $this->ga->verifyCode($registerResponse->secret, $registerResponse->code, 2);
    }
}


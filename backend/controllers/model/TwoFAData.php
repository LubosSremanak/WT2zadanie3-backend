<?php


class TwoFAData
{
    public string $code;
    public string $url;
    public string $secret;

    /**
     * TwoFAData constructor.
     * @param string $code
     * @param string $url
     * @param string $secret
     */
    public function __construct(string $code, string $url, string $secret)
    {
        $this->code = $code;
        $this->url = $url;
        $this->secret = $secret;
    }


}

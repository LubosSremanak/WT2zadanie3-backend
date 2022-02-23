<?php


class User
{
    public string $name;
    public string $email;
    public string $secret;

    /**
     * User constructor.
     * @param string $name
     * @param string $email
     * @param string $secret
     */
    public function __construct(string $name, string $email, string $secret)
    {
        $this->name = $name;
        $this->email = $email;
        $this->secret = $secret;
    }
}

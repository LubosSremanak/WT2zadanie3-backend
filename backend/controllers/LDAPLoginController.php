<?php

require_once "ChromePhp.php";

class LDAPLoginController
{
    /**
     * LDAPLoginController constructor.
     * @param $ldapId
     * @param $ldaPassword
     */
    private $ldapConnection;
    private string $domain = 'ou=People, DC=stuba, DC=sk';

    public function __construct($ldapId, $ldaPassword)
    {
        $ldapConnector = "uid=$ldapId, $this->domain";
        $this->ldapConnection = ldap_connect("ldap.stuba.sk")
        or die("Could not connect to LDAP server");

        if ($this->ldapConnection) {
            ldap_set_option($this->ldapConnection, LDAP_OPT_PROTOCOL_VERSION, 3);
            $ldapBind = ldap_bind($this->ldapConnection, $ldapConnector, $ldaPassword);
            if (!$ldapBind) {
                die ("\"Bind error\"");
            }
        }
    }

    function readDataById($ldapId)
    {
        $results = ldap_search($this->ldapConnection, $this->domain,
            "uid=$ldapId",
            array("givenname", "employeetype", "surname", "mail", "faculty", "cn", "uisid", "uid"),
            0, 5);
        $info = ldap_get_entries($this->ldapConnection, $results);
        echo json_encode(array("email" => $info[0]['mail'][0], "name" => $info[0]['cn'][0]));
    }
}

$login = json_decode(file_get_contents("php://input"))->login;
$ldap = new LDAPLoginController($login->name, $login->password);
$ldap->readDataById($login->name);

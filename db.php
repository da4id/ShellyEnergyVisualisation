<?php

function createPDO()
{
    $servername = "localhost";
    $username = "username";
    $password = "password";
    $dbname = "ShellyDB";

    return new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
}
?>

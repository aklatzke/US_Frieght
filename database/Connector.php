<?php

class Connector
{
    function __construct(){
        $this->connection = new PDO('mysql:host=localhost;dbname=USFreight;port=8888;charset=utf8', 'root', 'root');
    }

    function getConnection(){
        return $this->connection;
    }
}

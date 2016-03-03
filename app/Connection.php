<?php

class Connection
{

    public static function getConnection()
    {
        $conn = new mysqli("localhost", "USER", "PASSWORD", "DATABASE");
        $conn->query("SET NAMES 'utf8'");
        return $conn;
    }

}
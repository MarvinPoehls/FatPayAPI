<?php

namespace Src;

use PDO;

class Database
{
    protected $host;
    protected $dbName;
    protected $user;
    protected $password;

    protected $db;


    public function __construct($host, $dbName, $user, $password)
    {
        $this->host = $host;
        $this->dbName = $dbName;
        $this->user = $user;
        $this->password = $password;

        $dsn = "mysql:host=$this->host;dbname=$this->dbName;charset=utf8";

        $this->db = new PDO($dsn, $this->user, $this->password);
    }

    public function getConnection()
    {
        return $this->db;
    }
}
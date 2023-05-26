<?php 

class DBController
{
    public $dbHost = "localhost";
    public $dbUser = "root";
    public $dbPassword = "";
    public $dbName = "banking";
    public $connection;

    public function open()
    {
        $this->connection = mysqli_connect($this->dbHost, $this->dbUser, $this->dbPassword, $this->dbName);
        if (!$this->connection) {
            die("Connection failed: " . mysqli_connect_error());
        }
    }
}
?>
<?php

class Database
{
    private $conn;

    public function __construct($servername, $username, $password, $database)
    {

        $this->conn = mysqli_connect($servername, $username, $password, $database);

        if (!$this->conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
    }

    public function query($sql)
    {
        $result = mysqli_query($this->conn, $sql);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    public function query_for_one($sql){
        $result = mysqli_query($this->conn, $sql);
        if ($result) {
            if (mysqli_num_rows($result) == 0) {
                return null;
            }
            else {
                return mysqli_fetch_assoc($result);
            }
        }
        else{
            return mysqli_error($this->conn);
        }
    }

    public function execute($sql) { mysqli_query($this->conn, $sql); }

    public function __destruct() { mysqli_close($this->conn); }

}
?>
<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/startup.php';

class MysqlAgent
{
    private $mysql_conn;

    public function __construct()
    {

    }

    public function connect(): mysqli
    {
        //Create new mysql connection
        $this->mysql_conn = new mysqli(getenv('MYSQL_HOST'), getenv('MYSQL_USER'), getenv('MYSQL_PASS'), getenv('MYSQL_DB'));
        //Check error
        if ($this->mysql_conn->connect_error) {
            die("Connection failed: " . $this->mysql_conn->connect_error);
        }
        return $this->mysql_conn;
    }

    public function close()
    {
        $this->mysql_conn->close();
    }

    /**
     * @return mysqli
     */
    public function getMysqlConn(): mysqli
    {
        return $this->mysql_conn;
    }

    /**
     * @param mysqli $mysql_conn
     */
    public function setMysqlConn(mysqli $mysql_conn): void
    {
        $this->mysql_conn = $mysql_conn;
    }


}
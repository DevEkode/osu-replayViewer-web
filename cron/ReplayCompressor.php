<?php


class ReplayCompressor
{
    private static $life_days = 30;

    private $ftp_conn;

    public function __construct()
    {
        include 'secure/ftp.php';
    }

    private function connectToFTP()
    {
        $ftp_conn = ftp_connect($ftp_host);
        $login_result = ftp_login($conn_id, $ftp_user, $ftp_password);
    }

    /**
     *
     */
    private function getExpiredReplays()
    {

    }
}
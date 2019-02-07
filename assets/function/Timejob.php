<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);
include_once("config.php");
if (isset($_COOKIE["STUHELP"])) {
    session_id($_COOKIE["STUHELP"]);
    session_start();
    if (!isset($_SESSION["USER_ID"])) {
        header("Location: /login");
    }
    session_commit();
} else {
    session_destroy();
    header("Location: login.php");
}
class Timejob
{
    var $mysql_connection;
    public function __construct()
    {
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        $this->mysql_connection = new mysqli(DB_HOST, DB_USERNANE, DB_PASS, DB_SCHEM);
        $this->mysql_connection->set_charset("utf8");
    }


    public function __destruct()
    {
        $this->mysql_connection->close();
    }
}
?>
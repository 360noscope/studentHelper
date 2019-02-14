<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);
include_once("config.php");
class Authen
{
    var $mysql_connection, $database;
    public function __construct()
    {
        global $database;
        $this->database = $database;
    }

    public function login($data)
    {
        $result = array();
        try {
            $queryResult = $this->database->select(
                "users",
                array("userId", "password", "role"),
                array("email" => $data["email"])
            )[0];
            if (password_verify($data["password"], $queryResult["password"])) {
                $result["login"] = true;
                session_start();
                session_regenerate_id();
                $_SESSION["USER_ID"] = $queryResult["userId"];
                $_SESSION["ROLE"] = $queryResult["role"];
                setcookie("STUHELP", session_id(), time() + 3600, "/");
            } else {
                $result["login"] = false;
            }
        } catch (Exception $ex) {
            $result["error"] = $ex->getMessage();
        }
        return json_encode($result);
    }

    public function logout()
    {
        $result = array();
        try {
            session_id($_COOKIE["STUHELP"]);
            session_start();
            session_destroy();
            setcookie("STUHELP", "", time() - 9999);
        } catch (Exception $ex) {
            $result["error"] = $ex->getMessage();
        }
        return json_encode($result);
    }
}

$auth = new Authen();
$requestAction = $_POST["action"];
if ($requestAction == "login") {
    echo $auth->login($_POST["data"]);
} else if ($requestAction == "logout") {
    echo $auth->logout();
}
?>
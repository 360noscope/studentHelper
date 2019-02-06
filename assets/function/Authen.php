<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);
include_once("config.php");
class Authen
{
    var $mysql_connection;
    public function __construct()
    {
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        $this->mysql_connection = new mysqli(DB_HOST, DB_USERNANE, DB_PASS, DB_SCHEM);
        $this->mysql_connection->set_charset("utf8");
    }

    public function login($data)
    {
        $result = array();
        try {
            $stmt = $this->mysql_connection->prepare("SELECT userId, password, role FROM users WHERE email = ?");
            $stmt->bind_param("s", $data["email"]);
            $stmt->execute();
            $stmt->bind_param($id, $pass, $role);
            while ($stmt->execute()) {
                if (password_verify($data["password"], $pass)) {
                    $result["login"] = true;
                    session_start();
                    session_regenerate_id();
                    $_SESSION["USER_ID"] = $id;
                    $_SESSION["ROLE"] = $role;
                    setcookie("STUHELP", session_id(), time() + 3600, "/");
                } else {
                    $result["login"] = false;
                }
            }
            $stmt->close();
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
            setcookie("IDPSLoginID", "", time() - 9999);
        } catch (Exception $ex) {
            $result["error"] = $ex->getMessage();
        }
        return json_encode($result);
    }

    public function __destruct()
    {
        $this->mysql_connection->close();
    }
}

$auth = new Authen();
$requestAction = $_POST["action"];
if ($requestAction == "login") {
    echo $auth->login($data["data"]);
}else if($requestAction == "logout"){
    echo $auth->logout();
}
?>
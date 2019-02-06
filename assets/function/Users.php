<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include_once("config.php");
class Users
{
    var $mysql_connection;
    public function __construct()
    {
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        $this->mysql_connection = new mysqli(DB_HOST, DB_USERNANE, DB_PASS, DB_SCHEM);
        $this->mysql_connection->set_charset("utf8");
    }

    public function listUser()
    {
        $result = array();
        try {
            $stmt = $this->mysql_connection->prepare("SELECT users.userId, users.email, userdetail.name FROM users " .
                "JOIN userdetail ON users.userId = userdetail.userId");
            $stmt->execute();
            $stmt->bind_result($id, $email, $name);
            $deleteBtn = null;
            while ($stmt->fetch()) {
                if ($id == $_SESSION["USER_ID"]) {
                    $deleteBtn = "";
                } else {
                    $deleteBtn = null;
                }
                array_push($result, array($id, $email, $name, null, $deleteBtn));
            }
            $stmt->close();
        } catch (Exception $ex) {
            $result["error"] = $ex->getMessage();
        }
        return json_encode(array("data" => $result));
    }

    private function checkUsername($data)
    {
        $result = false;
        try {
            $stmt = $this->mysql_connection->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
            $stmt->bind_param("s", $data["email"]);
            $stmt->execute();
            $stmt->bind_result($count);
            while ($stmt->fetch()) {
                if ($count > 0) {
                    $result = true;
                }
            }
            $stmt->close();
        } catch (Exception $ex) {
            $result = $ex->getMessage();
        }
        return $result;
    }

    public function newUser($data)
    {
        $result = array();
        try {
            $isUserExists = $this->checkUsername($data);
            if ($isUserExists == true) {
                $result["userExists"] = $isUserExists;
            } else {
                $stmt = $this->mysql_connection->prepare("INSERT INTO users (email, password, role) VALUES(?, ?, ?)");
                $hashed = password_hash($data["password"], PASSWORD_DEFAULT);
                $stmt->bind_param("sss", $data["email"], $hashed, $data["role"]);
                $stmt->execute();
                $userId = $stmt->insert_id;

                $stmt = $this->mysql_connection->prepare("INSERT INTO userdetail (userId, name) VALUES(?, ?)");
                $stmt->bind_param("ss", $userId, $data["name"]);
                $stmt->execute();
                $stmt->close();
            }
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

$users = new Users();
$requestAction = $_POST["action"];
if ($requestAction == "listUser") {
    echo $users->listUser();
} else if ($requestAction == "insertUser") {
    echo $users->newUser($_POST["data"]);
}
?>
<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);
include_once("config.php");
if (isset($_COOKIE["STUHELP"])) {
    session_id($_COOKIE["STUHELP"]);
    session_start();
    if (!isset($_SESSION["USER_ID"])) {
        header("Location: login.php");
    }
    session_commit();
} else {
    session_destroy();
    header("Location: login.php");
}
class Users
{
    var $mysql_connection, $database;
    public function __construct()
    {
        global $database;
        $this->database = $database;
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        $this->mysql_connection = new mysqli(DB_HOST, DB_USERNANE, DB_PASS, DB_SCHEM);
        $this->mysql_connection->set_charset("utf8");
    }

    public function listUser()
    {
        $result = array();
        try {
            $queryResult = $this->database->select(
                "users",
                array("[<]userdetail" => array("userId" => "userId")),
                array("users.userId", "users.email", "userdetail.name")
            );
            foreach ($queryResult as $student) {
                if ($student["userId"] == $_SESSION["USER_ID"]) {
                    $deleteBtn = "";
                } else {
                    $deleteBtn = null;
                }
                array_push(
                    $result,
                    array(
                        $student["userId"],
                        $student["name"],
                        $student["email"],
                        null,
                        $deleteBtn
                    )
                );
            }
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

    public function getUserDetail($data)
    {
        $result = array();
        try {
            $stmt = $this->mysql_connection->prepare("SELECT users.email, users.role, userdetail.name FROM users " .
                "JOIN userdetail ON users.userId = userdetail.userId WHERE users.userId = ?");
            $stmt->bind_param("s", $data["id"]);
            $stmt->execute();
            $stmt->bind_result($email, $role, $name);
            while ($stmt->fetch()) {
                $result["email"] = $email;
                $result["role"] = $role;
                $result["name"] = $name;
            }
            $stmt->close();
        } catch (Exception $ex) {
            $result["error"] = $ex->getMessage();
        }

        return json_encode($result);
    }

    public function updateUser($data)
    {
        $result = array();
        try {
            $stmt = $this->mysql_connection->prepare("UPDATE userdetail SET name = ? WHERE userId = ?");
            $stmt->bind_param("ss", $data["name"], $data["editingUser"]);
            $stmt->execute();

            $queryStr = "UPDATE users SET email=?, X WHERE userId = ?";
            if ($data["emptyPassword"] == "NO") {
                $hashedPassword = password_hash($data["password"], PASSWORD_DEFAULT);
                $queryStr = str_replace("X", "password = ?", $queryStr);
                $stmt = $this->mysql_connection->prepare($queryStr);
                $stmt->bind_param("sss", $data["email"], $hashedPassword, $data["editingUser"]);
                $stmt->execute();
            } else {
                $queryStr = str_replace("X", "", $queryStr);
                $stmt = $this->mysql_connection->prepare($queryStr);
                $stmt->bind_param("ss", $data["email"], $data["editingUser"]);
                $stmt->execute();
            }
            $stmt->close();
        } catch (Exception $ex) {
            $result["error"] = $ex->getMessage();
        }
        return json_encode($result);
    }

    public function deleteUser($data)
    {
        $result = array();
        try {
            $stmt = $this->mysql_connection->prepare("DELETE FROM users WHERE userId = ?");
            $stmt->bind_param("s", $data["id"]);
            $stmt->execute();

            $stmt = $this->mysql_connection->prepare("DELETE FROM userdetail WHERE userId = ?");
            $stmt->bind_param("s", $data["id"]);
            $stmt->execute();
            $stmt->close();
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
} else if ($requestAction == "getUserDetail") {
    echo $users->getUserDetail($_POST["data"]);
} else if ($requestAction == "updateUser") {
    echo $users->updateUser($_POST["data"]);
} else if ($requestAction == "deleteUser") {
    echo $users->deleteUser($_POST["data"]);
}
?>
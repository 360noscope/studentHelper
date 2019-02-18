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
    var $database;
    public function __construct()
    {
        global $database;
        $this->database = $database;
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

    public function newUser($data)
    {
        $result = array();
        try {
            $existsUserCount = $this->database->count("users", array("email" => $data["email"]));
            if ($existsUserCount > 0) {
                $result["userExists"] = true;
            } else {
                $hashedPassword = password_hash($data["password"], PASSWORD_DEFAULT);
                $this->database->insert("users", array(
                    "email" => $data["email"],
                    "password" => $hashedPassword,
                    "role" => $data["role"]
                ));
                $userId = $this->database->id();
                $this->database->insert("userdetail", array(
                    "userId" => $userId,
                    "name" => $data["name"]
                ));
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
            $queryResult = $this->database->select(
                "users",
                array(
                    "[>]userdetail" => array("userId" => "userId"),
                ),
                array("users.email", "users.role", "userdetail.name"),
                array("users.userId" => $data["id"])
            )[0];
            $result["email"] = $queryResult["email"];
            $result["role"] = $queryResult["role"];
            $result["name"] = $queryResult["name"];
        } catch (Exception $ex) {
            $result["error"] = $ex->getMessage();
        }

        return json_encode($result);
    }

    public function updateUser($data)
    {
        $result = array();
        try {
            $this->database->update(
                "userdetail",
                array(
                    "name" => $data["name"]
                ),
                array("userId" => $data["editingUser"])
            );
            if ($data["emptyPassword"] == "NO") {
                $hashedPassword = password_hash($data["password"], PASSWORD_DEFAULT);
                $this->database->update(
                    "users",
                    array(
                        "email" => $data["email"],
                        "password" => $hashedPassword
                    ),
                    array("userId" => $data["editingUser"])
                );
            } else {
                $this->database->update(
                    "users",
                    array(
                        "email" => $data["email"]
                    ),
                    array("userId" => $data["editingUser"])
                );
            }
        } catch (Exception $ex) {
            $result["error"] = $ex->getMessage();
        }
        return json_encode($result);
    }

    public function deleteUser($data)
    {
        $result = array();
        try {
            $this->database->delete("users", array("userId" => $data["id"]));
            $this->database->delete("userdetail", array("userId" => $data["id"]));
        } catch (Exception $ex) {
            $result["error"] = $ex->getMessage();
        }
        return json_encode($result);
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
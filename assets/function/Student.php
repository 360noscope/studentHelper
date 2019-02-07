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
    header("Location: /login");
}
class Student
{
    var $mysql_connection;
    public function __construct()
    {
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        $this->mysql_connection = new mysqli(DB_HOST, DB_USERNANE, DB_PASS, DB_SCHEM);
        $this->mysql_connection->set_charset("utf8");
    }

    public function listClassroom()
    {
        $result = array();
        try {
            $stmt = $this->mysql_connection->prepare("SELECT classroomId, name FROM classroom");
            $stmt->execute();
            $stmt->bind_result($id, $name);
            while ($stmt->fetch()) {
                array_push($result, array("name" => $name, "id" => $id));
            }
            $stmt->close();
        } catch (Exception $ex) {
            $result["error"] = $ex->getMessage();
        }
        return json_encode($result);
    }

    public function insertClassroom($data)
    {
        $result = array();
        try {
            $stmt = $this->mysql_connection->prepare("INSERT INTO classroom (name) VALUES(?)");
            $stmt->bind_param("s", $data["name"]);
            $stmt->execute();
            $stmt->close();
        } catch (Exception $ex) {
            $result["error"] = $ex->getMessage();
        }
        return json_encode($result);
    }

    public function deleteClassroom($data)
    {
        $result = array();
        try {
            $stmt = $this->mysql_connection->prepare("DELETE FROM classroom WHERE classroomId = ?");
            $stmt->bind_param("s", $data["id"]);
            $stmt->execute();
            $stmt->close();
        } catch (Exception $ex) {
            $result["error"] = $ex->getMessage();
        }
        return json_encode($result);
    }

    public function listStudent($data)
    {
        $result = array();
        try {
            $stmt = $this->mysql_connection->prepare("SELECT studentId, name, prefix, nickname FROM student WHERE class =?");
            $stmt->bind_param("s", $data["classroom"]);
            $stmt->execute();
            $stmt->bind_result($id, $name, $prefix, $nickname);
            while ($stmt->fetch()) {
                array_push($result, array($id, $prefix . " " . $name, $nickname));
            }
            $stmt->close();
        } catch (Exception $ex) {
            $result["error"] = $ex->getMessage();
        }
        return json_encode(array("data" => $result));
    }

    public function insertStudent($data)
    {
        $result = array();
        try {
            $stmt = $this->mysql_connection->prepare("INSERT INTO student (studentId, prefix, name, nickname, class) " .
                "VALUES(?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $data["code"], $data["prefix"], $data["name"], $data["nick"], $data["room"]);
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

$student = new Student();
$requestAction = $_POST["action"];
if ($requestAction == "listClassroom") {
    echo $student->listClassroom();
} else if ($requestAction == "insetClassroom") {
    echo $student->insertClassroom($_POST["data"]);
} else if ($requestAction == "deleteClassroom") {
    echo $student->deleteClassroom($_POST["data"]);
} else if ($requestAction == "listStudent") {
    echo $student->listStudent($_POST["data"]);
}else if($requestAction == "insertStudent"){
    echo $student->insertStudent($_POST["data"]);
}
?>
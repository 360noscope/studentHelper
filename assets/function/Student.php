<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);
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
include_once("config.php");

class Student
{
    var $database;
    public function __construct()
    {
        global $database;
        $this->database = $database;
    }

    public function listClassroom()
    {
        $result = array();
        try {
            $queryResult = $this->database->select("classroom", array("classroomId", "name"));
            foreach ($queryResult as $classroom) {
                array_push($result, array("id" => $classroom["classroomId"], "name" => $classroom["name"]));
            }
        } catch (Exception $ex) {
            $result["error"] = $ex->getMessage();
        }
        return json_encode($result);
    }

    public function insertClassroom($data)
    {
        $result = array();
        try {
            $this->database->insert("classroom", array("name" => $data["name"]));
        } catch (Exception $ex) {
            $result["error"] = $ex->getMessage();
        }
        return json_encode($result);
    }

    public function deleteClassroom($data)
    {
        $result = array();
        try {
            $this->database->delete("classroom", array("classroomId" => $data["id"]));
        } catch (Exception $ex) {
            $result["error"] = $ex->getMessage();
        }
        return json_encode($result);
    }

    public function listStudent($data)
    {
        $result = array();
        try {
            $queryResult = $this->database->select(
                "student",
                array("studentId", "name", "prefix", "nickname"),
                array("class" => $data["classroom"])
            );
            foreach ($queryResult as $student) {
                array_push(
                    $result,
                    array(
                        $student["studentId"],
                        $student["prefix"] . " " . $student["name"],
                        $student["nickname"]
                    )
                );
            }
        } catch (Exception $ex) {
            $result["error"] = $ex->getMessage();
        }
        return json_encode(array("data" => $result));
    }


    public function insertStudent($data)
    {
        $result = array();
        try {
            $this->database->insert(
                "student",
                array(
                    "studentId" => $data["code"],
                    "prefix" => $data["prefix"],
                    "name" => $data["name"],
                    "nickname" => $data["nick"],
                    "class" => $data["room"]
                )
            );
        } catch (Exception $ex) {
            $result["error"] = $ex->getMessage();
        }
        return json_encode($result);
    }

    public function updateStudent($data)
    {
        $result = array();
        try {
            $this->database->update(
                "student",
                array(
                    "prefix" => $data["prefix"],
                    "name" => $data["name"],
                    "nickname" => $data["nick"],
                    "class" => $data["room"]
                ),
                array("studentId" => $data["code"])
            );
        } catch (Exception $ex) {
            $result["error"] = $ex->getMessage();
        }
        return json_encode($result);
    }

    public function deleteStudent($data)
    {
        $result = array();
        try {
            $this->database->delete("student", array("studentId" => $data["code"]));
        } catch (Exception $ex) {
            $result["error"] = $ex->getMessage();
        }
        return json_encode($result);
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
} else if ($requestAction == "insertStudent") {
    echo $student->insertStudent($_POST["data"]);
} else if ($requestAction == "updateStudent") {
    echo $student->updateStudent($_POST["data"]);
} else if ($requestAction == "deleteStudent") {
    echo $student->deleteStudent($_POST["data"]);
}
?>
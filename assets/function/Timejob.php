<?php 
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
error_reporting(E_ALL);
ini_set('display_errors', 1);
include_once("config.php");
class Timejob
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

    public function listSection($data)
    {
        $result = array();
        try {
            $queryResult = $this->database->select(
                "sectiontable",
                array(
                    "[>]timetable" => array("time" => "timeId")
                ),
                array("sectiontable.sectionId", "timetable.time", "sectiontable.subject", "sectiontable.day"),
                array("sectiontable.class" => $data["classroom"])
            );
            foreach ($queryResult as $section) {
                array_push(
                    $result,
                    array(
                        $section["sectionId"],
                        $section["subject"],
                        $section["day"],
                        $section["time"]
                    )
                );
            }
        } catch (Exception $ex) {
            $result['error'] = $ex->getMessage();
        }
        return json_encode(array("data" => $result));
    }

    public function deleteTime($data)
    {
        $result = array();
        try {
            $this->database->delete("timetable", array("timeId" => $data["time"]));
            $checkError = $this->database->error();
            if (count($checkError) > 1) {
                throw new Exception($checkError[2], $checkError[1]);
            } else {
                $this->database->query("ALTER TABLE timetable AUTO_INCREMENT = 1");
            }
        } catch (Exception $ex) {
            $result['error'] = $ex->getMessage();
            $result['errorCode'] = $ex->getCode();
        }
        return json_encode($result);
    }

    public function listAbsentName($data)
    {
        $result = array();
        try {
            $queryResult = null;
            $studentCheckList =
                $this->database->select(
                "sectiondetail",
                "studentId",
                array("AND" => array("sectionId" => $data["cell"], "date" => $data["date"]))
            );

            if (count($studentCheckList) == 0) {
                $queryResult = $this->database->select(
                    "student",
                    array("studentId", "name", "prefix")
                );
            } else {
                $queryResult = $this->database->select(
                    "student",
                    array("studentId", "name", "prefix"),
                    array("studentId[!]" => $studentCheckList)
                );
            }

            foreach ($queryResult as $student) {
                array_push($result, array($student["studentId"], $student["prefix"] . " " . $student["name"]));
            }
        } catch (Exception $ex) {
            $result["error"] = $ex->getMessage();
        }
        return json_encode(array("data" => $result));
    }

    public function listPresentName($data)
    {
        $result = array();
        try {
            $queryResult = null;
            $studentCheckList =
                $this->database->select(
                "sectiondetail",
                "studentId",
                array("AND" => array("sectionId" => $data["cell"], "date" => $data["date"]))
            );

            if (count($studentCheckList) >= 0) {
                $queryResult = $this->database->select(
                    "student",
                    array("studentId", "name", "prefix"),
                    array(
                        "studentId" =>
                            $studentCheckList
                    )
                );
            }

            foreach ($queryResult as $student) {
                array_push($result, array($student["studentId"], $student["prefix"] . " " . $student["name"]));
            }
        } catch (Exception $ex) {
            $result["error"] = $ex->getMessage();
        }
        return json_encode(array("data" => $result));
    }

    public function studentCheckin($data)
    {
        $result = array();
        try {
            $this->database->delete("sectiondetail", array("AND" =>
                array(
                "sectionId" => $data["cell"],
                "date" => $data["date"]
            )));
            $this->database->query("ALTER TABLE sectiondetail AUTO_INCREMENT = 1");
            foreach ($data["studentPresentList"] as $student) {
                $this->database->insert("sectiondetail", array(
                    "sectionId" => $data["cell"],
                    "studentId" => $student[0],
                    "date" => $data["date"]
                ));
            }
        } catch (Exception $ex) {
            $result["error"] = $ex->getMessage();
        }
        return json_encode($result);
    }

    public function listTime()
    {
        $result = array();
        try {
            $queryResult = $this->database->select(
                "timeTable",
                array("timeId", "time")
            );
            foreach ($queryResult as $time) {
                array_push($result, array("id" => $time["timeId"], "time" => $time["time"]));
            }
        } catch (Exception $ex) {
            $result['error'] = $ex->getMessage();
        }
        return json_encode($result);
    }

    public function insertTime($data)
    {
        $result = array();
        try {
            $this->database->insert(
                "timeTable",
                array("time" => $data["time"])
            );
        } catch (Exception $ex) {
            $result['error'] = $ex->getMessage();
        }
        return json_encode($result);
    }

    public function listTravel($data)
    {
        $result = array();
        try {
            $queryResult = $this->database->select(
                "traveldata",
                array("[>]student" => array("studentId" => "studentId")),
                array(
                    "travelId",
                    "prefix",
                    "name",
                    "date",
                    "time",
                    "type"
                ),
                array("student.class" => $data["classroom"])
            );
            foreach ($queryResult as $travel) {
                array_push($result, array(
                    $travel["travelId"],
                    $travel["prefix"] . $travel["name"],
                    $travel["date"] . " " . $travel["time"],
                    $travel["type"]
                ));
            }
        } catch (Exception $ex) {
            $result['error'] = $ex->getMessage();
        }
        return json_encode(array("data" => $result));
    }

    public function insertTravel($data)
    {
        $result = array();
        try {
            $currentDate = date('Y-m-d', time());
            $currentTime = date('h:i:s', time());
            $type = "มาโรงเรียน";
            $cometoschool = $this->database->has(
                "traveldata",
                array("AND" => array(
                    "studentId" => $data["id"],
                    "type" => "มาโรงเรียน",
                    "date" => $currentDate
                ))
            );
            if ($cometoschool == true) {
                $type = "กลับบ้าน";

            }
            $this->database->insert(
                "traveldata",
                array(
                    "studentId" => $data["id"],
                    "date" => $currentDate,
                    "time" => $currentTime,
                    "type" => $type
                )
            );
        } catch (Exception $ex) {
            $result['error'] = $ex->getMessage();
        }
        return json_encode($result);
    }

    public function homeCheck($data)
    {
        $result = array();
        try {
            $currentDate = date('Y-m-d', time());
            $comehome = $this->database->has(
                "traveldata",
                array("AND" => array(
                    "studentId" => $data["id"],
                    "type" => "กลับบ้าน",
                    "date" => $currentDate
                ))
            );
            $result["result"] = $comehome;
        } catch (Exception $ex) {
            $result['error'] = $ex->getMessage();
        }
        return json_encode($result);
    }

    public function deleteTravel($data)
    {
        $result = array();
        try {
            $this->database->delete("traveldata", array("travelId" => $data["id"]));
        } catch (Exception $ex) {
            $result['error'] = $ex->getMessage();
        }
        return json_encode($result);
    }

    public function insertSubjectCell($data)
    {
        $result = array();
        try {
            $duplicateTime = false;
            $stmt = $this->mysql_connection->prepare("SELECT COUNT(*) FROM sectiontable WHERE class = ? AND time = ? AND day = ?");
            $stmt->bind_param("sss", $data["room"], $data["time"], $data["day"]);
            $stmt->execute();
            $stmt->bind_result($dupeCount);
            while ($stmt->fetch()) {
                if ($dupeCount > 0) {
                    $duplicateTime = true;
                }
            }
            if ($duplicateTime == true) {
                $result["error"] = "DUPE";
            } else {
                $stmt = $this->mysql_connection->prepare("INSERT INTO sectiontable (class, time, subject, day) VALUES(?, ?, ?, ?)");
                $stmt->bind_param("ssss", $data["room"], $data["time"], $data["subject"], $data["day"]);
                $stmt->execute();
            }
            $stmt->close();
        } catch (Exception $ex) {
            $result['error'] = $ex->getMessage();
        }
        return json_encode($result);
    }

    public function deleteSubjectCell($data)
    {
        $result = array();
        try {
            $stmt = $this->mysql_connection->prepare("DELETE FROM sectiontable WHERE sectionId = ?");
            $stmt->bind_param("s", $data["id"]);
            $stmt->execute();
            $stmt = $this->mysql_connection->prepare("ALTER TABLE sectiontable AUTO_INCREMENT = 1");
            $stmt->execute();
            $stmt->close();
        } catch (Exception $ex) {
            $result['error'] = $ex->getMessage();
        }
        return json_encode($result);
    }

    public function __destruct()
    {
        $this->mysql_connection->close();
    }
}

$timer = new Timejob();
$requestAction = $_POST["action"];
if ($requestAction == "listSection") {
    echo $timer->listSection($_POST["data"]);
} else if ($requestAction == "listTime") {
    echo $timer->listTime();
} else if ($requestAction == "insertTime") {
    echo $timer->insertTime($_POST["data"]);
} else if ($requestAction == "insertSubjectCell") {
    echo $timer->insertSubjectCell($_POST["data"]);
} else if ($requestAction == "deleteSection") {
    echo $timer->deleteTime($_POST["data"]);
} else if ($requestAction == "listStudentSession") {
    echo $timer->listStudentSession($_POST["data"]);
} else if ($requestAction == "listAbsentName") {
    echo $timer->listAbsentName($_POST["data"]);
} else if ($requestAction == "listPresentName") {
    echo $timer->listPresentName($_POST["data"]);
} else if ($requestAction == "checkinStudent") {
    echo $timer->studentCheckin($_POST["data"]);
} else if ($requestAction == "listTravel") {
    echo $timer->listTravel($_POST["data"]);
} else if ($requestAction == "insertTravel") {
    echo $timer->insertTravel($_POST["data"]);
} else if ($requestAction == "homeCheck") {
    echo $timer->homeCheck($_POST["data"]);
}else if($requestAction =="deleteTravel"){
    echo $timer->deleteTravel($_POST["data"]);
}
?>
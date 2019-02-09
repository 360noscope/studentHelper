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
    var $mysql_connection;
    public function __construct()
    {
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        $this->mysql_connection = new mysqli(DB_HOST, DB_USERNANE, DB_PASS, DB_SCHEM);
        $this->mysql_connection->set_charset("utf8");
    }

    public function listSection($data)
    {
        $result = array();
        try {
            $stmt = $this->mysql_connection->prepare("SELECT sectiontable.sectionId, timetable.time, " .
                "sectiontable.subject, sectiontable.day FROM sectiontable " .
                "JOIN timetable ON sectiontable.time = timetable.timeId WHERE sectiontable.class = ?");
            $stmt->bind_param("s", $data["classroom"]);
            $stmt->execute();
            $stmt->bind_result($id, $time, $subject, $day);
            while ($stmt->fetch()) {
                array_push($result, array($id, $subject, $day, $time));
            }
            $stmt->close();
        } catch (Exception $ex) {
            $result['error'] = $ex->getMessage();
        }
        return json_encode(array("data" => $result));
    }

    public function deleteTime($data)
    {
        $result = array();
        try {
            $stmt = $this->mysql_connection->prepare("DELETE FROM timetable WHERE timeId = ?");
            $stmt->bind_param("s", $data["time"]);
            $stmt->execute();
            $stmt = $this->mysql_connection->prepare("ALTER TABLE timetable AUTO_INCREMENT = 1");
            $stmt->execute();
            $stmt->close();
        } catch (Exception $ex) {
            $result['error'] = $ex->getMessage();
            $result['errorCode'] = $ex->getCode();
        }
        return json_encode($result);
    }

    public function listTime()
    {
        $result = array();
        try {
            $stmt = $this->mysql_connection->prepare("SELECT timeId, time FROM timeTable");
            $stmt->execute();
            $stmt->bind_result($id, $time);
            while ($stmt->fetch()) {
                array_push($result, array("id" => $id, "time" => $time));
            }
            $stmt->close();
        } catch (Exception $ex) {
            $result['error'] = $ex->getMessage();
        }
        return json_encode($result);
    }

    public function insertTime($data)
    {
        $result = array();
        try {
            $stmt = $this->mysql_connection->prepare("INSERT INTO timeTable (time) VALUES(?)");
            $stmt->bind_param("s", $data["time"]);
            $stmt->execute();
            $stmt->close();
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
} else if ($requestAction == "deleteSubjectCell") {
    echo $timer->deleteSubjectCell($_POST["data"]);
}
?>
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
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>การรับส่ง/เช็คชื่อนักเรียน</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="assets/vendor/bootstrap/css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="assets/vendor/datatable/datatables.min.css" />
    <link rel="stylesheet" type="text/css" href="assets/vendor/daterangepicker/daterangepicker.css">
    <link rel="stylesheet" type="text/css" media="screen" href="assets/css/about.css" />
    <script src="assets/vendor/jquery/jquery.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="assets/vendor/datatable/datatables.min.js"></script>
    <script src="assets/vendor/daterangepicker/moment.min.js"></script>
    <script src="assets/vendor/daterangepicker/daterangepicker.js"></script>
    <script src="assets/js/timejob.js"></script>
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">ระบบช่วยเหลือนักเรียน</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive"
                aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="home.php">หน้าหลัก
                            <span class="sr-only">(current)</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="about.php">ประวัติผู้พัฒนาระบบ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="student.php">ข้อมูลนักเรียน</a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="timejob.php">การรับส่ง/เช็คชื่อนักเรียน</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="users.php">ข้อมูลผู้ใช้งาน</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="logout" href="#">ออกจากระบบ</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Page Content -->
    <div class="container topper">
        <div class="card border-0 shadow my-5">
            <div class="card-body p-5">
                <h1 class="font-weight-light">ข้อมูลเช็คชื่อ/รับส่งนักเรียน</h1>
                <form onsubmit="return false;">
                    <div class="form-inline" style="width:auto;">
                        <label for="classroomList">ห้องรียน</label>
                        <select id="classroomList" class="custom-select mr-2 ml-2">
                        </select>
                        <button class="btn btn-info" data-toggle="modal"
                            data-target="#travelDialog">รับ/ส่งนักเรียน</button>
                    </div>
                </form>
                <br />
                <table id="timeTable" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th></th>
                            <th class="text-center">วิชา</th>
                            <th class="text-center">วัน</th>
                            <th class="text-center">เวลา</th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <hr>
                <div class="col-9">
                    <h3 class="font-weight-light">ข้อมูลตารางเรียน</h3>
                    <form method="POST" id="subjectCellForm">
                        <div class="form-inline">
                            <label>เลือกวันเรียน</label>
                            <select class="custom-select ml-2 mb-2" id="sectionDay">
                                <option value="อาทิตย์">วันอาทิตย์</option>
                                <option value="จันทร์">วันจันทร์</option>
                                <option value="อังคาร">วันอังคาร</option>
                                <option value="พุธ">วันพุธ</option>
                                <option value="พฤหัสบดี">วันพฤหัสบดี</option>
                                <option value="ศุกร์">วันศุกร์</option>
                                <option value="เสาร์">วันเสาร์</option>
                            </select>
                        </div>
                        <div class="form-inline">
                            <label>เลือกคาบเรียน</label>
                            <select class="custom-select ml-2 mb-2" id="sectionTime">
                            </select>
                            <button type="button" class="btn btn-primary ml-2 mb-2" data-toggle="modal"
                                data-target="#addSection">เพิ่มคาบเรียน</button>
                            <button type="button" class="btn btn-warning ml-2 mb-2"
                                id="deleteSection">ลบคาบเรียน</button>
                        </div>
                        <div class="form-inline">
                            <label>วิชา</label>
                            <input class="form-control ml-2 mb-2" id="sectionSubject" required />
                        </div>
                        <div class="form-inline float-right">
                            <button class="btn btn-success" type="submit">บันทึก</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="travelDialog" role="dialog">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">ข้อมูลรับส่งนักเรียนห้อง <strong class="text-danger"
                            id="roomSelect"></strong></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table id="traveltable" class="table table-striped table-bordered" style="width: 100%">
                        <thead>
                            <tr>
                                <th></th>
                                <th>ชื่อ-นามสกุล</th>
                                <th>วัน-เวลา</th>
                                <th>ประเภท</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                    <form method="POST" id="travelform">
                        <div class="form-inline col-6">
                            <label class="mr-2 mb-2">เลือกนักเรียน</label>
                            <select class="custom-select mb-2 mr-2 col-6" id="travelStudentList"></select>
                            <button class="btn btn-success mb-2" id="travelSubmitBtn" type="submit">ลงเวลารับ-ส่ง</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addSection" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">เพิ่มคาบเรียน</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" id="sectionForm">
                    <div class="modal-body">
                        <div class="form-inline">
                            <label>เลือกเวลา</label>
                            <input id="timeSelector" class="form-control m-3" required readonly />
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">บันทึก</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">ยกเลิก</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="viewStudent" role="dialog">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">เช็คชื่อนักเรียน</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" id="studentCheckForm">
                        <div class="form-inline row ml-2 mb-4">
                            <label>เลือกวันที่</label>
                            <input class="form-control ml-2" id="sessionDate" readonly required />
                        </div>
                        <h3 class="font-weight-light mb-4">นักเรียนที่ไม่เข้าเรียน</h3>
                        <table id="absentStudentTable" class="table table-striped table-bordered" style="width: 100%">
                            <thead>
                                <tr>
                                    <th class="text-center">รหัสประจำตัว</th>
                                    <th class="text-center">ชื่อ</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                        <h3 class="font-weight-light mb-4">นักเรียนที่เข้าเรียน</h3>
                        <table id="presentStudent" class="table table-striped table-bordered" style="width: 100%">
                            <thead>
                                <tr>
                                    <th class="text-center">รหัสประจำตัว</th>
                                    <th class="text-center">ชื่อ</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">บันทึก</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">ยกเลิก</button>
                </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>
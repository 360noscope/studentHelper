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
    <title>เช็คชื่อนักเรียน</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="assets/vendor/bootstrap/css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="assets/vendor/datatable/datatables.min.css" />
    <link rel="stylesheet" type="text/css" href="assets/vendor/jquery-ui/jquery-ui.min.css" />
    <link rel="stylesheet" type="text/css" media="screen" href="assets/css/about.css" />
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
                    <li class="nav-item active">
                        <a class="nav-link" href="student.php">ข้อมูลนักเรียน</a>
                    </li>
                    <li class="nav-item">
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
                <h1 class="font-weight-light">รายชื่อนักเรียน</h1>
                <form onsubmit="return false;">
                    <div class="form-inline" style="width:auto;">
                        <label for="classroomList">ห้องรียน</label>
                        <select id="classroomList" class="custom-select mr-2 ml-2">
                        </select>
                        <button data-toggle="modal" data-target="#addClassroom"
                            class="btn btn-success mr-2">เพิ่มห้องเรียน</button>
                        <button data-toggle="modal" data-target="#deleteClassroom"
                            class="btn btn-danger">ลบห้องเรียน</button>
                    </div>
                </form>
                <br />
                <table id="studentTable" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center">รหัสประจำตัว</th>
                            <th class="text-center">ชื่อ - นามสกุล</th>
                            <th class="text-center">ชื่อเล่น</th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <hr />
                <div class="col-6">
                    <h4 class="font-weight-light">ข้อมูลนักเรียน</h4>
                    <form method="POST" id="studentForm">
                        <div class="form-inline">
                            <label>คำนำหน้า</label>
                            <select id="prefixList" class="custom-select ml-2">
                                <option>นาย</option>
                                <option>นางสาว</option>
                                <option>เด็กหญิง</option>
                                <option>เด็กชาย</option>
                            </select>
                        </div>
                        <div class="form-inline mt-2">
                            <label>รหัสนักเรียน</label>
                            <input id="studentCode" class="form-control ml-2" style="width: auto" required />
                        </div>
                        <div class="form-inline mt-2">
                            <label>ชื่อ-นามสกุล</label>
                            <input id="studentName" class="form-control ml-2" style="width: auto" required />
                        </div>
                        <div class="form-inline mt-2">
                            <label>ชื่อเล่น</label>
                            <input id="studentNick" class="form-control ml-2" style="width: auto" required />
                        </div>
                        <div class="form-inline mt-2 float-sm-right">
                            <button class="btn btn-success" value="newStudent" type="submit">บันทึก</button>
                            <button class="btn btn-danger ml-2" type="reset">ยกเลิก</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="addClassroom" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">เพิ่มห้องเรียน</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" id="classroomForm">
                    <div class="modal-body">
                        <div class="form-inline">
                            <label>ชื่อห้องเรียน</label>
                            <input id="classname" class="form-control m-3" required />
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

    <div class="modal fade" id="deleteClassroom" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">ลบห้องเรียน</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>ต้องการลบห้องเรียน <strong id="classroom"></strong> หรือไม่?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" id="deleteClassroomBtn" class="btn btn-success">ลบ</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">ยกเลิก</button>
                </div>
            </div>
        </div>
    </div>
    <script src="assets/vendor/jquery/jquery.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="assets/vendor/datatable/datatables.min.js"></script>
    <script type="text/javascript" src="assets/vendor/jquery-ui/jquery-ui.min.js"></script>
    <script src="assets/js/student.js"></script>
</body>

</html>
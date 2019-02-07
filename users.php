<?php 
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
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>ข้อมูลผู้ใช้งาน</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="assets/vendor/bootstrap/css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" media="screen" href="assets/css/about.css" />
    <link rel="stylesheet" type="text/css" href="assets/vendor/datatable/datatables.min.css" />
    <link rel="stylesheet" type="text/css" href="assets/vendor/jquery-ui/jquery-ui.min.css" />
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
                    <li class="nav-item">
                        <a class="nav-link" href="timejob.php">การรับส่ง/เช็คชื่อนักเรียน</a>
                    </li>
                    <li class="nav-item active">
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
                <h1 class="font-weight-light">รายชื่อผู้ใช้งานระบบ</h1>
                <br>
                <table id="userTable" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th></th>
                            <th>ชื่อ</th>
                            <th>อีเมลล์</th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <hr />
                <div class="col-6">
                    <form id="userForm" method="POST">
                        <h4 class="font-weight-light">ข้อมูลของผู้ใช้งานระบบ</h4>
                        <div class="form-group">
                            <label>อีเมลล์</label>
                            <input type="email" id="userEmail" placeholder="example@xxx.com" class="form-control"
                                required></div>
                        <div class="form-row">
                            <div class="form-group col-6">
                                <label>รหัสผ่าน</label>
                                <input type="password" class="form-control" id="userPass" required>
                            </div>
                            <div class="form-group col-6">
                                <label>ยืนยันรหัสผ่าน</label>
                                <input type="password" class="form-control" id="confirmPass" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>ประเภทผู้ใช้งาน</label>
                            <select id="userRole" class="custom-select">
                                <option value="admin">ผู้ดูแลระบบ</option>
                                <option value="user">ผู้ใช้งานระบบ</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>ชื่อ-นามสกุล</label>
                            <input id="name" class="form-control" required>
                        </div>
                        <button type="submit" value="newUser" class="btn btn-success float-right">บันทึก</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="assets/vendor/jquery/jquery.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="assets/vendor/datatable/datatables.min.js"></script>
    <script type="text/javascript" src="assets/vendor/jquery-ui/jquery-ui.min.js"></script>
    <script src="assets/js/users.js"></script>
</body>

</html>
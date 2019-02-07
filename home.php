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
    <title>ระบบช่วยเหลือนักเรียน</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="assets/vendor/bootstrap/css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" media="screen" href="assets/css/home.css" />
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
                    <li class="nav-item active">
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
                        <a class="nav-link" href="#">การรับส่ง/เช็คชื่อนักเรียน</a>
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

    <header>
        <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
            <ol class="carousel-indicators">
                <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
            </ol>
            <div class="carousel-inner" role="listbox">
                <!-- Slide One - Set the background image for this slide in the line below -->
                <div class="carousel-item active" style="background-image: url('https://source.unsplash.com/AWidiBoRO08/1920x1080')">
                    <div class="carousel-caption d-none d-md-block">
                        <h2 class="display-4">เช็คชื่อนักเรียนทุกที่ทุกเวลา</h2>
                        <p class="lead">ครูผู้สอนสามารถทำการเช็คชื่อนักเรียนภายในนี้ได้จากทุกที่ ทุกเวลา</p>
                    </div>
                </div>
                <!-- Slide Two - Set the background image for this slide in the line below -->
                <div class="carousel-item" style="background-image: url('https://source.unsplash.com/NsPDiPFTp4c/1920x1080')">
                    <div class="carousel-caption d-none d-md-block">
                        <h2 class="display-4">ข้อมูลนักเรียนไม่มีตกหล่น</h2>
                        <p class="lead">ระบบสามารถจัดเก็บข้อมูลนักเรียนได้อย่างครบถ้วน</p>
                    </div>
                </div>
                <!-- Slide Three - Set the background image for this slide in the line below -->
                <div class="carousel-item" style="background-image: url('https://source.unsplash.com/ezfJtvNx2Ig/1920x1080')">
                    <div class="carousel-caption d-none d-md-block">
                        <h2 class="display-4">นักเรียนไม่มีหลงทาง</h2>
                        <p class="lead">ครูผู้สอนสามารถติดตามข้อมูลการรับส่งนักเรียนได้</p>
                    </div>
                </div>
            </div>
            <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>
    </header>

    <!-- Page Content -->
    <section class="py-5">
        <div class="container">
            <h1 class="display-4">ระบบช่วยเหลือนักเรียน</h1>
            <p class="lead">ระบบการช่วยเหลือนักเรียน</a>!</p>
        </div>
    </section>
    <script src="assets/vendor/jquery/jquery.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/home.js"></script>
</body>

</html>
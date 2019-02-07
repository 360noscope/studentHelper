var studentTable;
$(document).ready(function () {
    listClass();

    $(document).on("click", "#logout", function (e) {
        e.preventDefault();
        $.ajax({
            type: 'POST',
            url: './assets/function/Authen.php',
            data: {
                action: "logout"
            },
            success: function (data) {
                window.location.replace("./login.php");
            }
        });
    });

    $(document).on("submit", "#classroomForm", function (e) {
        e.preventDefault();
        $.ajax({
            type: 'POST',
            url: './assets/function/Student.php',
            data: {
                action: "insetClassroom",
                data: {
                    "name": function () { return $("#classname").val() }
                }
            },
            success: function (data) {
                $("#addClassroom").modal("hide");
                listClass();
                $("#classroomForm").trigger("reset");
            }
        });
    });

    var selectedClassroom;
    $('#deleteClassroom').on('show.bs.modal', function (e) {
        selectedClassroom = $("#classroomList").val();
        $("#classroom").html($("#classroomList option:selected").text());
    });

    $(document).on("click", "#deleteClassroomBtn", function () {
        $.ajax({
            type: 'POST',
            url: './assets/function/Student.php',
            data: {
                action: "deleteClassroom",
                data: {
                    "id": selectedClassroom
                }
            },
            success: function (data) {
                listClass();
                $("#deleteClassroom").modal("hide");
            }
        });
    });

    $(document).on("submit", "#studentForm", function (e) {
        var btnType = $("button[type=submit]").val();
        e.preventDefault();
        if (btnType == "newStudent") {
            $.ajax({
                type: 'POST',
                url: './assets/function/Student.php',
                data: {
                    action: "insertStudent",
                    data: {
                        code: function () { return $("#studentCode").val() },
                        prefix: function () { return $("#prefixList option:selected").text() },
                        name: function () { return $("#studentName").val() },
                        nick: function () { return $("#studentNick").val() },
                        room: function () { return $("#classroomList").val() }
                    }
                },
                success: function (data) {
                    listStudent();
                    $("#studentForm").trigger("reset");
                }
            });
        } else if (btnType == "updateStudent") {

        }
    });

    $(document).on("click", "#editRecord", function () {
        var selectedStudent = studentTable.row($(this).parents('tr')).data();
        var cleanse = selectedStudent[1].split(" ")
            .filter(function (elem) {
                return elem != "";
            });
        $("#studentCode").attr("disabled", true);
        $("#studentCode").val(selectedStudent[0]);
        $("#prefixList").val(cleanse[0]);
        $("#studentName").val(cleanse[1] + " " + cleanse[2]);
        $("#studentNick").val(selectedStudent[2]);
    });
});

function listClass() {
    $.ajax({
        type: 'POST',
        url: './assets/function/Student.php',
        data: {
            action: "listClassroom"
        },
        success: function (data) {
            returnData = JSON.parse(data);
            $("#classroomList").empty();
            $.each(returnData, function (key, val) {
                var opt = new Option(val["name"], val["id"]);
                $("#classroomList").append(opt);
            });
            listStudent();
        }
    });
}

function listStudent() {
    if (studentTable != null) {
        studentTable.ajax.reload();
    } else {
        studentTable = $('#studentTable').DataTable({
            info: false,
            processing: true,
            language: {
                processing: "กำลังโหลดข้อมูล...",
                search: "ค้นหา:",
                lengthMenu: "จำนวนที่แสดงผล _MENU_ รายการ",
                loadingRecords: "กำลังโหลดข้อมูล...",
                zeroRecords: "ไม่มีข้อมูลในระบบ",
                emptyTable: "ไม่มีข้อมูลในระบบ",
                paginate: {
                    first: "รายการแรก",
                    previous: "ก่อนหน้า",
                    next: "ถัดไป",
                    last: "รายการสุดท้าย"
                }
            },
            columnDefs: [
                {
                    targets: [0],
                    className: "text-center"
                },
                {
                    targets: [2],
                    className: "text-center"
                },
                {
                    targets: [3],
                    className: "text-center",
                    defaultContent: "<button class='btn btn-warning' id='editRecord'>แก้ไข</button>"
                },
                {
                    targets: [4],
                    className: "text-center",
                    defaultContent: "<button class='btn btn-danger' id='deleteRecord'>ลบ</button>"
                }
            ],
            ajax: {
                url: "./assets/function/Student.php",
                type: "POST",
                data: {
                    action: "listStudent",
                    data: {
                        classroom: function () { return $("#classroomList").val() }
                    }
                }
            }
        });
    }
}


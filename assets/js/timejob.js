var classTable, absentTable, presentTable, cellSelected;
$(document).ready(function () {

    listClass();
    listTime();

    $('#timeSelector').daterangepicker({
        timePicker: true,
        timePicker24Hour: true,
        linkedCalendars: false,
        autoUpdateInput: true,
        timePickerIncrement: 1,
        timePickerSeconds: false,
        locale: {
            format: 'HH:mm'
        }
    }).on('show.daterangepicker', function (ev, picker) {
        picker.container.find(".calendar-table").hide();
    });

    $(document).on("submit", "#sectionForm", function (e) {
        e.preventDefault();
        $.ajax({
            type: 'POST',
            url: './assets/function/Timejob.php',
            data: {
                action: "insertTime",
                data: {
                    time: function () { return $("#timeSelector").val() }
                }
            },
            success: function (data) {
                returnData = JSON.parse(data);
                console.log(returnData);
                listTime();
                $("#addSection").modal("hide");
            }
        });
    });

    $(document).on("submit", "#subjectCellForm", function (e) {
        e.preventDefault();
        $.ajax({
            type: 'POST',
            url: './assets/function/Timejob.php',
            data: {
                action: "insertSubjectCell",
                data: {
                    room: function () { return $("#classroomList").val() },
                    time: function () { return $("#sectionTime").val() },
                    subject: function () { return $("#sectionSubject").val() },
                    day: function () { return $("#sectionDay").val() }
                }
            },
            success: function (data) {
                var returnData = JSON.parse(data);
                if (returnData.hasOwnProperty("error")) {
                    if (returnData["error"] == "DUPE") {
                        alert("คาบเรียนในวันดังกล่าวนั้นไม่ว่างแล้ว");
                    }
                }
                $("#sectionSubject").val("");
                listSection();
            }
        });
    });

    $(document).on("click", "#deleteSection", function (e) {
        $.ajax({
            type: 'POST',
            url: './assets/function/Timejob.php',
            data: {
                action: "deleteSection",
                data: {
                    time: function () { return $("#sectionTime").val() }
                }
            },
            success: function (data) {
                var returnData = JSON.parse(data);
                if (returnData.hasOwnProperty("error")) {
                    if (returnData["errorCode"] == "1451") {
                        alert("ไม่สามารถลบข้อมูลคาบเรียนดังกล่าวได้เนื่องจากมีการใช้งานค่ะ");
                    }
                }
                listTime();
            }
        });
    });

    $(document).on("click", "#viewStudentList", function (e) {
        var selectedCell = classTable.row($(this).parents('tr')).data();
        cellSelected = selectedCell[0];
        $('#sessionDate').daterangepicker({
            singleDatePicker: true,
            linkedCalendars: false,
            autoUpdateInput: true,
            locale: {
                format: 'DD-MM-YYYY'
            }
        }).on('apply.daterangepicker', function (ev, picker) {
            listStudentSchedule();
        });
        listStudentSchedule();
        $("#viewStudent").modal("show");
    });

    $(document).on("submit", "#studentCheckForm", function (e) {
        e.preventDefault();
        var presentList = [];
        presentTable.rows().every(function (rowIdx, tableLoop, rowLoop) {
            var stuData = this.data();
            presentList.push(stuData);
        });

        $.ajax({
            type: 'POST',
            url: './assets/function/Timejob.php',
            data: {
                action: "checkinStudent",
                data: {
                    studentPresentList: presentList,
                    cell: cellSelected,
                    date: function () { return $("#sessionDate").val() }
                }
            },
            success: function (data) {
                $("#viewStudent").modal("hide");
            }
        });
    });

    $(document).on("click", "#present", function () {
        var selectedStu = absentTable.row($(this).parents('tr')).data();
        presentTable.row.add([selectedStu[0], selectedStu[1]]).draw(false);
        absentTable.row($(this).parents('tr'))
            .remove()
            .draw();
    });

    $(document).on("click", "#absent", function () {
        var selectedStu = presentTable.row($(this).parents('tr')).data();
        absentTable.row.add([selectedStu[0], selectedStu[1]]).draw(false);
        presentTable.row($(this).parents('tr'))
            .remove()
            .draw();
    });

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

    $(document).on("click", "#deleteRecord", function () {
        var selectedCell = classTable.row($(this).parents('tr')).data();
        $.ajax({
            type: 'POST',
            url: './assets/function/Timejob.php',
            data: {
                action: "deleteSubjectCell",
                data: {
                    id: function () { return selectedCell[0] }
                }
            },
            success: function (data) {
                var returnData = JSON.parse(data);
                listSection();
            }
        });
    });

    $(document).on("change", "#classroomList", function () {
        listSection();
    });
});

function listStudentSchedule() {
    if (absentTable != null || presentTable != null) {
        absentTable.ajax.reload();
        presentTable.ajax.reload();
    }
    else {
        absentTable = $('#absentStudentTable').DataTable({
            info: false,
            processing: true,
            lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
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
                    targets: [1],
                    className: "text-center"
                },
                {
                    targets: [2],
                    className: "text-center",
                    defaultContent: "<button class='btn btn-success' id='present'>เลือก</button>"
                }
            ],
            ajax: {
                url: "./assets/function/Timejob.php",
                type: "POST",
                data: {
                    action: "listAbsentName",
                    data: {
                        date: function () { return $("#sessionDate").val() },
                        cell: cellSelected
                    }
                }
            }
        });

        presentTable = $('#presentStudent').DataTable({
            info: false,
            processing: true,
            lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
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
                    targets: [1],
                    className: "text-center"
                },
                {
                    targets: [2],
                    className: "text-center",
                    defaultContent: "<button class='btn btn-warning' id='absent'>เลือก</button>"
                }
            ],
            ajax: {
                url: "./assets/function/Timejob.php",
                type: "POST",
                data: {
                    action: "listPresentName",
                    data: {
                        cell: cellSelected,
                        date: function () { return $("#sessionDate").val() }
                    }
                }
            }
        });
    }
}

function listTime() {
    $.ajax({
        type: 'POST',
        url: './assets/function/Timejob.php',
        data: {
            action: "listTime"
        },
        success: function (data) {
            returnData = JSON.parse(data);
            $("#sectionTime").empty();
            $.each(returnData, function (key, val) {
                var opt = new Option("คาบที่: " + val["id"] + " เวลา: " + val["time"], val["id"]);
                $("#sectionTime").append(opt);
            });
        }
    });
}

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
            listSection();
        }
    });
}

function listSection() {
    if (classTable != null) {
        classTable.ajax.reload();
    } else {
        classTable = $('#timeTable').DataTable({
            info: false,
            processing: true,
            lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
            rowGroup: {
                startRender: function (rows, group) {
                    return $('<tr>')
                        .append('<td colspan="100">วัน' + group + '</td>');
                },
                dataSrc: 2
            },
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
                    orderable: false,
                    searchable: false,
                    visible: false
                },
                {
                    targets: [1],
                    className: "text-center"
                },
                {
                    targets: [2],
                    className: "text-center",
                    visible: false
                },
                {
                    targets: [3],
                    className: "text-center"
                },
                {
                    targets: [4],
                    className: "text-center",
                    defaultContent: "<button class='btn btn-danger' id='deleteRecord'>ลบ</button>"
                },
                {
                    targets: [5],
                    className: "text-center",
                    defaultContent: "<button class='btn btn-warning' id='viewStudentList'>เช็คชื่อ</button>"
                }
            ],
            ajax: {
                url: "./assets/function/Timejob.php",
                type: "POST",
                data: {
                    action: "listSection",
                    data: {
                        classroom: function () { return $("#classroomList").val() }
                    }
                }
            }
        });
    }
}
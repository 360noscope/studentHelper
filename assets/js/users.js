var userTable;
$(document).ready(function () {
    userTable = $('#userTable').DataTable({
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
        columnDef: [
            {
                targets: [0],
                orderable: false,
                searchable: false,
                visible: false
            },
            {
                targets: [2],
                className: "text-center",
                defaultContent: "<button class='btn btn-warning' id='editRecord'>แก้ไข</button>"
            },
            {
                targets: [3],
                className: "text-center",
                defaultContent: "<button class='btn btn-danger' id='deleteRecord'>ลบ</button>"
            }
        ],
        "ajax": {
            "url": "./assets/function/Users.php",
            "type": "POST",
            "data": {
                "action": "listUser"
            }
        }
    });

    $(document).on("submit", "#userForm", function (e) {
        e.preventDefault();
        if ($("#confirmPass").val() !== $("#userPass").val()) {
            alert("รหัสผ่านทั้งสองช่องไม่เหมือนกันค่ะ");
        } else {
            $.ajax({
                type: 'POST',
                url: './assets/function/Users.php',
                data: {
                    action: "insertUser",
                    data: {
                        email: function () { return $("#userEmail").val() },
                        name: function () { return $("#name").val() },
                        password: function () { return $("#userPass").val() },
                        role: function () { return $("#userRole").val() }
                    }
                },
                success: function (data) {
                    var returnData = JSON.parse(data);
                    if (returnData.hasOwnProperty("userExists")) {
                        alert("มีการใช้ email นี้แล้ว");
                    } else {
                        userTable.ajax.reload();
                    }
                }
            });
        }
    });
});
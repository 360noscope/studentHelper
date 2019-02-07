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
        columnDefs: [
            {
                targets: [0],
                orderable: false,
                searchable: false,
                visible: false
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
        "ajax": {
            "url": "./assets/function/Users.php",
            "type": "POST",
            "data": {
                "action": "listUser"
            }
        }
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

    var selectedId;
    $(document).on("submit", "#userForm", function (e) {
        e.preventDefault();
        var btnType = $("button[type=submit]").val();
        if (btnType == "newUser") {
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
                            $('#userForm').trigger("reset");
                            userTable.ajax.reload();
                        }
                    }
                });
            }
        } else if (btnType == "updateUser") {
            var noPassword = "YES", passwordCheck = false;
            if ($.trim($("#userPass").val()).length > 0) {
                if ($("#confirmPass").val() !== $("#userPass").val()) {
                    alert("รหัสผ่านทั้งสองช่องไม่เหมือนกันค่ะ");
                } else {
                    passwordCheck = true;
                    noPassword = "NO";
                }
            } else {
                passwordCheck = true;
                noPassword = "YES";
            }

            if (passwordCheck == true) {
                $.ajax({
                    type: 'POST',
                    url: './assets/function/Users.php',
                    data: {
                        action: "updateUser",
                        data: {
                            email: function () { return $("#userEmail").val() },
                            name: function () { return $("#name").val() },
                            password: function () { return $("#userPass").val() },
                            role: function () { return $("#userRole").val() },
                            editingUser: selectedId,
                            emptyPassword: noPassword
                        }
                    },
                    success: function (data) {
                        var returnData = JSON.parse(data);
                        if (returnData.hasOwnProperty("userExists")) {
                            alert("มีการใช้ email นี้แล้ว");
                        } else {
                            $('#userForm').trigger("reset");
                            $("#userForm button[type=submit]").val("newUser");
                            userTable.ajax.reload();
                        }
                    }
                });
            }
        }
    });


    $(document).on("click", "#editRecord", function () {
        var selectedUser = userTable.row($(this).parents('tr')).data();
        selectedId = selectedUser[0];
        $.ajax({
            type: 'POST',
            url: './assets/function/Users.php',
            data: {
                action: "getUserDetail",
                data: {
                    id: selectedId
                }
            },
            success: function (data) {
                var returnData = JSON.parse(data);
                $("#userEmail").val(returnData["email"]);
                $("#userRole").val(returnData["role"]);
                $("#name").val(returnData["name"]);
                $("#userPass").attr("placeholder", "[รหัสผ่านเดิม]");
                $("#userPass").removeAttr("required");
                $("#confirmPass").attr("placeholder", "[รหัสผ่านเดิม]");
                $("#confirmPass").removeAttr("required");
                $("#userForm button[type=submit]").val("updateUser");
            }
        });
    });

    $(document).on("click", "#deleteRecord", function () {
        var selectedUser = userTable.row($(this).parents('tr')).data();
        var message = "ต้องการจะลบ user ที่ใช้ email <strong>" + selectedUser[2] +
            "</strong> หรือไม่?";
        $('<div></div>').appendTo('body')
            .html('<div><h6>' + message + '?</h6></div>')
            .dialog({
                modal: true, title: 'ระบบช่วยเหลือนักเรียน', zIndex: 10000, autoOpen: true,
                width: 'auto', resizable: false,
                buttons: {
                    Yes: function () {
                        $.ajax({
                            type: 'POST',
                            url: './assets/function/Users.php',
                            data: {
                                action: "deleteUser",
                                data: {
                                    id: selectedUser[0]
                                }
                            },
                            success: function (data) {
                                userTable.ajax.reload();
                            }
                        });
                        $(this).dialog("close");
                    },
                    No: function () {
                        $(this).dialog("close");
                    }
                },
                close: function (event, ui) {
                    $(this).remove();
                }
            });
    });
});

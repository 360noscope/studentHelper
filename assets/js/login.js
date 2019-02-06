$(document).ready(function () {
    $(document).on("submit", "#loginForm", function(e){
        e.preventDefault();
        $.ajax({
            type: 'POST',
            url: './assets/function/Authen.php',
            data: {
                action: "login",
                data: {
                    email: function () { return $("#inputEmail").val() },
                    password: function () { return $("#inputPassword").val() }
                }
            },
            success: function (data) {
                var returnData = JSON.parse(data);
                if(returnData["login"] == false){
                    $(".wrongpass").toast('show');
                }else{

                }
            }
        });
    });
});
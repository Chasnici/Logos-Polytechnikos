
function SignUp() {

    $.ajax({
        type: "POST",
        dataType: "json",
        data: { "email" : $("#email-input").val(), "pwd" : $("#pwd-input").val(), "pwdconfirm" : $("#pwdconfirm-input").val(), "role" : $("#role-input").val() },
        url: "http://localhost/rsp/signup",
        success: function(data){
            if (data.result == 0) {
                $("#signup-alert").attr("style", "display: inline-block");
                $("#signup-alert").removeClass();
                $("#signup-alert").addClass("alert");
                $("#signup-alert").addClass("alert-danger");
                $("#signup-alert").text("Hesla se neshodují.");
                $("#signup-alert").attr("style", "width:100%");
            }
            if (data.result == 1) {
                $("#signup-alert").attr("style", "display: inline-block");
                $("#signup-alert").removeClass();
                $("#signup-alert").addClass("alert");
                $("#signup-alert").addClass("alert-success");
                $("#signup-alert").text("Uživatel zaregistrován.");
                $("#signup-alert").attr("style", "width:100%");
            }
        }
    });
    
}

function Login() {

    $.ajax({
        type: "POST",
        dataType: "json",
        data: { "email" : $("#email-login-input").val(), "pwd" : $("#pwd-login-input").val() },
        url: "http://localhost/rsp/login",
        success: function(data){
            if (data.result == 0) {
                $("#login-alert").attr("style", "display: inline-block");
                $("#login-alert").removeClass();
                $("#login-alert").addClass("alert");
                $("#login-alert").addClass("alert-danger");
                $("#login-alert").text("Špatné jméno nebo heslo.");
                $("#login-alert").attr("style", "width:100%");
            }
            if (data.result == 1) {
                window.open(window.location.href,"_self")
            }
        }
    });
    
}

function Logout() {

    $.ajax({
        type: "POST",
        dataType: "json",
        url: "http://localhost/rsp/logout",
        success: function(data){
            // if (data.result == 0) {
            //     $("#login-alert").attr("style", "display: inline-block");
            //     $("#login-alert").removeClass();
            //     $("#login-alert").addClass("alert");
            //     $("#login-alert").addClass("alert-danger");
            //     $("#login-alert").text("Špatné jméno nebo heslo.");
            //     $("#login-alert").attr("style", "width:100%");
            // }
            if (data.result == 1) {
                window.open(window.location.href,"_self")
            }
        }
    });
    
}

function SendMessage() {

    $.ajax({
        type: "POST",
        dataType: "json",
        data: { "sendto" : $("#message-sendto").val(), "subject" : $("#message-subject").val(), "message" : $("#message-message").val() },
        url: "http://localhost/rsp/sendmessage",
        success: function(data){
            // if (data.result == 0) {
            //     $("#login-alert").attr("style", "display: inline-block");
            //     $("#login-alert").removeClass();
            //     $("#login-alert").addClass("alert");
            //     $("#login-alert").addClass("alert-danger");
            //     $("#login-alert").text("Špatné jméno nebo heslo.");
            //     $("#login-alert").attr("style", "width:100%");
            // }
            if (data.result == 1) {
                // window.open(window.location.href,"_self")
            }
        }
    });
    
}

$("#btn-signup").click(function(){
   SignUp();
});

$("#btn-login").click(function(){
    Login();
});

$("#btn-logout").click(function(){
    Logout();
});

$("#btn-sendmessage").click(function(){
    SendMessage();
});

$("#signup-navbar").click(function(){
    $("#signup-alert").attr("style", "display: none");
});

$("#login-navbar").click(function(){
    $("#login-alert").attr("style", "display: none");
});



function SignUp() {

    $.ajax({
        type: "POST",
        dataType: "json",                                                                                                                  
        data: { "email" : $("#email-input").val() + "@redakce.vspj.cz", "pwd" : $("#pwd-input").val(), "pwdconfirm" : $("#pwdconfirm-input").val(), "role" : $('#role-input').children(":selected").attr("id") },
        url: "https://195.113.207.163/~ruzick34/rsp_ver2/index.php/signup",
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
        url: "https://195.113.207.163/~ruzick34/rsp_ver2/index.php/login",
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
        url: "https://195.113.207.163/~ruzick34/rsp_ver2/index.php/logout",
        success: function(data){
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
        data: { "sendto" : $('#message-sendto').children(":selected").attr("id"), "subject" : $("#message-subject").val(), "message" : $("#message-message").val() },
        url: "https://195.113.207.163/~ruzick34/rsp_ver2/index.php/sendmessage",
        success: function(data){
            if (data.result == 1) {
                $("#message-alert").attr("style", "display: inline-block");
                $("#message-alert").removeClass();
                $("#message-alert").addClass("alert");
                $("#message-alert").addClass("alert-success");
                $("#message-alert").text("Zpráva odeslána.");
                $("#message-alert").attr("style", "width:100%");
            }
        }
    });
    
}

function SendHelpDeskMessage() {

    $.ajax({
        type: "POST",
        dataType: "json",           
        data: { "sendto" : $('#message-sendto').children(":selected").attr("id"), "subject" : $("#message-subject").val(), "message" : $("#message-message").val() },
        url: "https://195.113.207.163/~ruzick34/rsp_ver2/index.php/sendhelpdeskmessage",
        success: function(data){
            if (data.result == 1) {
                $("#messagehelpdesk-alert").attr("style", "display: inline-block");
                $("#messagehelpdesk-alert").removeClass();
                $("#messagehelpdesk-alert").addClass("alert");
                $("#messagehelpdesk-alert").addClass("alert-success");
                $("#messagehelpdesk-alert").text("Zpráva odeslána.");
                $("#messagehelpdesk-alert").attr("style", "width:100%");
            }
        }
    });

}

$("#btn-signup").click(function(){

    if (!$("#email-input").val() || (!$("#pwd-input").val()) || (!$("#pwdconfirm-input").val())) {
        $("#signup-alert").attr("style", "display: inline-block");
        $("#signup-alert").removeClass();
        $("#signup-alert").addClass("alert");
        $("#signup-alert").addClass("alert-danger");
        $("#signup-alert").text("Nevyplněné pole.");
        $("#signup-alert").attr("style", "width:100%");
    } else {
        SignUp();
    }

});

$("#btn-login").click(function(){

    if (!$("#email-login-input").val() || (!$("#pwd-login-input").val())) {
        $("#login-alert").attr("style", "display: inline-block");
        $("#login-alert").removeClass();
        $("#login-alert").addClass("alert");
        $("#login-alert").addClass("alert-danger");
        $("#login-alert").text("Nevyplněné pole.");
        $("#login-alert").attr("style", "width:100%");
    } else {
        Login();
    }

});

$("#btn-logout").click(function(){
    Logout();
});

$("#btn-sendmessage").click(function(){
    SendMessage();
});

$("#btn-sendmessagehelpdesk").click(function(){
    SendHelpDeskMessage();
});

$("#signup-navbar").click(function(){
    $("#signup-alert").attr("style", "display: none");
});

$("#login-navbar").click(function(){
    $("#login-alert").attr("style", "display: none");
});



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
        data: { "email" : $("#email-login-input").val() + "@redakce.vspj.cz", "pwd" : $("#pwd-login-input").val() },
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

function MakeArticlePublic(articleID) {

    $.ajax({
        type: "POST",
        dataType: "json",           
        data: { "articleID" : articleID },
        url: "https://195.113.207.163/~ruzick34/rsp_ver2/index.php/makearticlepublic",
        success: function(data){
            if (data.result == 1) {
                $(".articleheader" + articleID).removeClass("bg-warning");
                $(".articleheader" + articleID).removeClass("bg-danger");
                $(".articleheader" + articleID).addClass("bg-success");
                $(".articlefooter" + articleID).removeClass("bg-warning");
                $(".articlefooter" + articleID).removeClass("bg-danger");
                $(".articlefooter" + articleID).addClass("bg-success");
                $(".articlestatus" + articleID).text("Schváleno: Ano");
                $("#btn-article-confirm" + articleID).attr("style", "display: none");
                $("#btn-article-decline" + articleID).attr("style", "display: none");
            }
        }
    });

}


function MakeArticleNotPublic(articleID) {

    $.ajax({
        type: "POST",
        dataType: "json",           
        data: { "articleID" : articleID },
        url: "https://195.113.207.163/~ruzick34/rsp_ver2/index.php/makearticlenotpublic",
        success: function(data){
            if (data.result == 1) {
                $(".articleheader" + articleID).removeClass("bg-warning");
                $(".articleheader" + articleID).addClass("bg-danger");
                $(".articlefooter" + articleID).removeClass("bg-warning");
                $(".articlefooter" + articleID).addClass("bg-danger");
                $(".articlestatus" + articleID).text("Publikováno: Ne");
                //$("#btn-article-confirm" + articleID).attr("style", "display: none");
                $("#btn-article-decline" + articleID).attr("style", "display: none");
            }
        }
    });

}

function SelectReviewer(articleID) {

    $.ajax({
        type: "POST",
        dataType: "json",           
        data: { "reviewerID" : $('#reviewer-input').children(":selected").attr("id"), "articleID" : articleID },
        url: "https://195.113.207.163/~ruzick34/rsp_ver2/index.php/selectreviewer",
        success: function(data){
            if (data.result == 1) {
                $("#reviewerselection-alert").attr("style", "display: inline-block");
                $("#reviewerselection-alert").removeClass();
                $("#reviewerselection-alert").addClass("alert");
                $("#reviewerselection-alert").addClass("alert-success");
                $("#reviewerselection-alert").text("Recenzent přiřazen");
                $("#reviewerselection-alert").attr("style", "width:100%");
            } else {
                $("#reviewerselection-alert").attr("style", "display: inline-block");
                $("#reviewerselection-alert").removeClass();
                $("#reviewerselection-alert").addClass("alert");
                $("#reviewerselection-alert").addClass("alert-danger");
                $("#reviewerselection-alert").text("Recenzent nepřiřazen");
                $("#reviewerselection-alert").attr("style", "width:100%");
            }
        }
    });

}

function MakeReview(articleID) {

    $.ajax({
        type: "POST",
        dataType: "json",           
        data: { 
            "recency" : $('#recency-input').children(":selected").val(), 
            "interesting" : $('#interesting-input').children(":selected").val(), 
            "originality" : $('#originality-input').children(":selected").val(), 
            "professional" : $('#professional-input').children(":selected").val(), 
            "stylistic" : $('#stylistic-input').children(":selected").val(), 
            "articleID" : articleID
        },
        url: "https://195.113.207.163/~ruzick34/rsp_ver2/index.php/makereview",
        success: function(data){
            if (data.result == 1) {
                $("#reviewerselection-alert").attr("style", "display: inline-block");
                $("#reviewerselection-alert").removeClass();
                $("#reviewerselection-alert").addClass("alert");
                $("#reviewerselection-alert").addClass("alert-success");
                $("#reviewerselection-alert").text("Recenzent přiřazen");
                $("#reviewerselection-alert").attr("style", "width:100%");
            } else {
                $("#reviewerselection-alert").attr("style", "display: inline-block");
                $("#reviewerselection-alert").removeClass();
                $("#reviewerselection-alert").addClass("alert");
                $("#reviewerselection-alert").addClass("alert-danger");
                $("#reviewerselection-alert").text("Recenzent nepřiřazen");
                $("#reviewerselection-alert").attr("style", "width:100%");
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

$(".btn-reviewer-select").click(function(){
    $('#btn-select-reviewer').attr('name', $(this).attr("name"));
});

$("#btn-select-reviewer").click(function(){
    SelectReviewer($(this).attr("name"));
});

$(".btn-article-review").click(function(){
    $('#btn-review-confirm').attr('name', $(this).attr("name"));
});

$("#btn-review-confirm").click(function(){
    MakeReview($(this).attr("name"));
});

$(".btn-article-decline").click(function(){
    MakeArticleNotPublic($(this).attr("name"));
});

$(".btn-article-confirm").click(function(){
    MakeArticlePublic($(this).attr("name"));
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


function checkRegisterInput() {
    var passwordInput = document.getElementById("password").value;
    var pwdCheck = document.getElementById("pwdConfirm").value;
    var n = passwordInput.localeCompare(pwdCheck);

    if (n != 0) {
        alert("密碼輸入不一致");
    }

    checkAccountID();
}

function checkAccountID() {
    var accountInput = document.getElementById("account").value;
    var emailInput = document.getElementById("email").value;
    var posting = $.post("register_check.php", {
        AccountID: accountInput,
        Email: emailInput
    });

    posting.done(function(data) {
        var response = $.parseJSON(data);
        if (response.success == 0) {
            if (response.account == 1) {
                alert("此帳號已被註冊");
            } else if (response.email) {
                alert("此email已被註冊");
            }
        } else {
            register();
        }

    });
}

function register() {
    var postToReg = $.post("register.php", {
        AccountID: document.getElementById("account").value,
        Password: document.getElementById("password").value,
        Name: document.getElementById("realName").value,
        Email: document.getElementById("email").value,
        Company: document.getElementById("company").value,
        Previlege: document.getElementById("privilege").value
    });

    postToReg.done(function(data) {
        var response = $.parseJSON(data);
        console.log(response);
        if (response.success == 1) {
            alert("註冊成功！！！");
            window.location.href = "login.html";
        } else {
            alert("註冊失敗");
        }
    });
}

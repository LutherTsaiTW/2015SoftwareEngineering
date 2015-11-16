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
    var posting = $.post("registerCheck.php", {
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
    var accountInput = document.getElementById("account").value;
    var PswdInput = document.getElementById("password").value;
    var nameInput = document.getElementById("realName").value;
    var emailInput = document.getElementById("email").value;
    var companyInput = document.getElementById("company").value;
    var privilegeInput = document.getElementById("privilege").value;

    var postToReg = $.post("register.php", {
        AccountID: accountInput,
        PasswordInput: PswdInput,
        Name: nameInput,
        Email: emailInput,
        Company: companyInput,
        Privilege: privilegeInput
    });


    postToReg.done(function(data) {
        var response = $.parseJSON(data);
        if (response.success == 1) {
            alert("註冊成功！！！");
            window.location.href = "login.html";
        } else {
            alert("註冊失敗");
        }
    });
}

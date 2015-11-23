function checkOnLogin() {
    var account = document.getElementById("account").value;
    var password = document.getElementById("password").value;
    var posting = $.post("login.php", {
        account_id: account,
        password: password
    });

    posting.done(function(data) {
        var response = $.parseJSON(data);
        if (response.success == 0) {
            alert("登入失敗！\n帳號或密碼錯誤？(" + response.message + ")");
        } else {
            document.location.href = "./Project/projectList.html";
        }
    });
}

function checkOnLogin() {
    var account = document.getElementById("account").value;
    var password = document.getElementById("password").value;
    var posting = $.post("login.php", {
        account_id: account,
        password: password
    });

    posting.done(function(data) {
        try {
            var response = $.parseJSON(data);
        } catch (err) {
            alert("Parsing JSON Fail!: " + err.message + "\nJSON: " + data);
        }
        if (response.success == 0) {
            alert("登入失敗！\n帳號或密碼錯誤？(" + response.message + ")");
        } else {
            document.location.href = "./Project/projectList.html";
        }
    });
}

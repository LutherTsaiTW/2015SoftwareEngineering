var xmlhttp = new XMLHttpRequest();
var login_page = "../login.html";
var session_check = "../assist/sessionCheck.php";

xmlhttp.onreadystatechange = function() {
    if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
        check(xmlhttp.responseText);
    }
}

xmlhttp.open("POST", session_check, false);
xmlhttp.send();

function check(response) {
    if (response == "NULL") {
        document.location.href = login_page;
    }
}

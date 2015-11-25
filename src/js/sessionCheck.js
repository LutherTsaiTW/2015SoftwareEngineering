var xmlhttp = new XMLHttpRequest();
var url = "../assist/sessionCheck.php";

xmlhttp.open("GET", url, false);
xmlhttp.send();

if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
    if (xmlhttp.responseText == "NULL") {
        document.location.href = "../login.html";
    }
} else {
    alert("XmlHttp get " + url + " fail.");
}

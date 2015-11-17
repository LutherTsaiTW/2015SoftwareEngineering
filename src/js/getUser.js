
var xmlhttp = new XMLHttpRequest();
var url = "projectList.php";

xmlhttp.open("GET", url, true);
xmlhttp.send();

xmlhttp.onreadystatechange=function() {
    if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
        insertWelcomeGreeting(xmlhttp.responseText);
    }
}


function insertWelcomeGreeting(response) {
    var arr = JSON.parse(response);
	document.getElementById("userName").innerHTML ="Welcome, "+arr.name+"!";
}

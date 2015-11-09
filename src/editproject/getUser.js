
var xmlhttp = new XMLHttpRequest();
var url = "projectlist.php";

xmlhttp.open("GET", url, true);
xmlhttp.send();

xmlhttp.onreadystatechange=function() {
    if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
        myFunction(xmlhttp.responseText);
    }
}


function myFunction(response) {
    var arr = JSON.parse(response);
	document.getElementById("userName").innerHTML ="Welcome, "+arr.name+"!";
}

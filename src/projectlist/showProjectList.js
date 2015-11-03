
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
	var project=arr.projects;
    var i;
    var out = "<table class=\"listTable\">";

    for(i = 1; i < project.length; i++) {
        out += "<tr><td>" + 
        project[i].name +
        "</td><td>" +
        project[i].des +
        "</td><td>" +
        "edit" +
        "</td></tr>";
    }
    out += "</table>";
	document.getElementById("userName").innerHTML ="Welcome, "+arr.name+"!";
    document.getElementById("listTable").innerHTML =out;
}

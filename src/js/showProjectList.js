var xmlhttp = new XMLHttpRequest();
var url = "projectList.php";

xmlhttp.open("GET", url, true);
xmlhttp.send();

xmlhttp.onreadystatechange = function() {
    if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
        insertHTML(xmlhttp.responseText);
    }
}


//顯示是否刪除的對話框
function showDeleteWindow(i) {
    document.getElementById("deleteWindow").style.top = document.documentElement.clientHeight / 2 - 75;
    document.getElementById("deleteWindow").style.left = document.documentElement.clientWidth / 2 - 200;

    document.getElementById("deleteWindow").style.visibility = "visible";
    document.getElementById("block").style.visibility = "visible";
    document.getElementById("deleteButton").href = "deleteProject.php?pid=" + i;
}

//關閉刪除的對話框
function closeDeleteWindow() {
    document.getElementById("block").style.visibility = "hidden";
    document.getElementById("deleteWindow").style.visibility = "hidden";
}

function insertHTML(response) {
    try{
        var arr = $.parseJSON(response);
    }catch (err){
        alert("error: " + err.message);
    }
      
    var out = "<table class=\"listTable\"> <tr ><td><b>Name</b></td><td><b>Company</b></td><td><b>Owner</b></td><td><b>Start Time</b></td><td><b>End Time</b></td><td><b>Status</b></td><td> </td><td> </td></tr>";
    document.getElementById("userName").innerHTML = "Welcome, " + arr.name + "!";
    if (arr.projects) {
        var project = arr.projects;
        project = Object.keys(project).map(function(k) {
            return project[k]
        });
        var i;
        for (i = 0; i < project.length; i++) {
            if (project[i].status != 3) {
                out += "<tr><td><a href=\"projectDetailView.php?pid=" + project[i].pid + "\">" +
                    project[i].name +
                    "</a></td><td>" +
                    project[i].company +
                    "</td><td>" +
                    project[i].owner +
                    "</td><td>" +
                    project[i].start_time.substr(0, 10) +
                    "</td><td>" +
                    project[i].end_time.substr(0, 10) +
                    "</td><td>";
                if (project[i].status == 0) out += "Close";
                else if (project[i].status == 1) out += "Open";
                else if (project[i].status == 2) out += "Terminated";
                out += "</td> ";
                if (project[i].owner == arr.name && (arr.previlege == 777 || arr.previlege == 999)) {
                    out += "<td style=\"text-align:right;\"><a href=\"editProjectView.php?pid=" + project[i].pid + "&from=0\">Edit</a></td><td ><a onclick=\"showDeleteWindow(" + project[i].pid + ")\">Delete</a></td></tr>";
                } else {
                    out += "<td></td><td></td></tr>";
                }
            }
        }

    }
    if (arr.previlege == 777 || arr.previlege == 999) {
		out += "<tr><td><a href=\"addProject.html\"><b>Add Project</b></a></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr></table>";
	}
    document.getElementById("listTable").innerHTML = out;
}

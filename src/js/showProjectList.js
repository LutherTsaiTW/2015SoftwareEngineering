
var xmlhttp = new XMLHttpRequest();
var url = "projectList.php";

xmlhttp.open("GET", url, true);
xmlhttp.send();

xmlhttp.onreadystatechange=function() {
    if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
        myFunction(xmlhttp.responseText);
    }
}


function myFunction(response) {
    var arr = JSON.parse(response);
	var out = "<table class=\"listTable\"> <tr ><td><b>Name</b></td><td><b>Owner</b></td><td><b>Company</b></td><td><b>Start Time</b></td><td><b>End Time</b></td><td><b>Status</b></td><td></td><td></td></tr>";
	document.getElementById("userName").innerHTML ="Welcome, "+arr.name+"!";
	if(arr.projects)
	{
		var project=arr.projects;
		project = Object.keys(project).map(function(k) { return project[k] });
		var i;
		for(i = 0; i < project.length; i++) {
			out += "<tr><td><a href=\"projectDetail.php?pid="+project[i].pid+"\">" + 
			project[i].name +
			"</a></td><td>" +
			project[i].owner +
			"</td><td>" +
			project[i].company +
			"</td><td>" +
			project[i].start_time.substr(0,10) +
			"</td><td>" +
			project[i].end_time.substr(0,10) + 
			"</td><td>" +
			project[i].status+
			"</td> ";
			if(project[i].owner == arr.name)
			{
				out+="<td style=\"text-align:right;\"><a href=\"editProjectView.php?pid="+project[i].pid+"\">edit</a></td><td ><a href=\"projectDelete.html\">delete</a></td></tr>";
			}else{
				out+="<td></td><td></td></tr>";
			}
			
		}
		
	}
	out += "<tr><td><a href=\"addProject.html\"><b>Add Project</b></a></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr></table>";
	document.getElementById("listTable").innerHTML =out;
}

function getData(rid){
	document.getElementById("rid").value=rid;
	var pid = document.getElementById("pid").value
	showRelationWindow();

	var xmlhttp2 = new XMLHttpRequest();
	var url2 = "getRequirementRelation.php?rid=" + rid + "&pid=" + pid + "&relation=1";

	xmlhttp2.open("GET", url2, false);
	xmlhttp2.send();
	insertIntoList(xmlhttp2.responseText, "inList");

	var xmlhttp3 = new XMLHttpRequest();
	var url3 = "getRequirementRelation.php?rid=" + rid + "&pid=" + pid + "&relation=0";

	xmlhttp3.open("GET", url3, false);
	xmlhttp3.send();
	insertIntoList(xmlhttp3.responseText, "notInList");
}

function insertIntoList(response, listName) {
	//alert(response);
	//alert(listName);
	document.getElementById(listName).options.length=0;
	if(response){
		var arr = JSON.parse(response);
		//alert(arr.length);
		for(var i = 0;i < arr.length;i++){
			var option = new Option(arr[i].rname, arr[i].rid);
			//alert(option);
			document.getElementById(listName).options.add(option);
		}
	}
}

function showRelationWindow() {
	document.getElementById("rightBlock").style.visibility = "visible";
	if( document.getElementById("changed_rids")!=null){
		document.getElementById("changed_rids").options.length=0;
	}
}
function getData(rid){
	document.getElementById("rid").value = rid;
	var xmlhttp1 = new XMLHttpRequest();
	var url1 = "getRequirement.php?rid=" + rid;

	xmlhttp1.open("GET", url1, false);
	xmlhttp1.send();
	var res = $.parseJSON(xmlhttp1.responseText);	
	document.getElementById("editReq").innerHTML = "Requirement : " + res.rname;
	
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
		var arr = $.parseJSON(response);
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

function doRemove() {
	var inList=document.getElementById("inList");
	var notInList=document.getElementById("notInList");

	var length = inList.options.length - 1;
	if(inList.options.length>0){
		for (var i = length; i >= 0; i--) {
			if (inList.options[i].selected) {
				var newOption =inList.options[i];
				if(!inChanged(newOption))
					newOption.style.background="red";
				else
					newOption.style.background="white";
				notInList.options.add(newOption);           
			}
		}
	}
	
	setNotSelect(inList);
	setNotSelect(notInList);
	updateConfirmButton();
}


function  doAdd(){
	var inList=document.getElementById("inList");
	var notInList=document.getElementById("notInList");

	var length = notInList.options.length - 1;
	if(notInList.options.length>0){
		for (var i = length; i >= 0; i--) {
			if (notInList.options[i].selected) {
				var newOption =notInList.options[i];
				if(!inChanged(newOption))
					newOption.style.background="red";
				else
					newOption.style.background="white";
				inList.options.add(newOption);
			}
		}
	}

	setNotSelect(inList);
	setNotSelect(notInList);
	updateConfirmButton();
}

function setNotSelect(zone) {
	var length = zone.options.length - 1;
	for(var i = length;i >= 0; i--)
	{
		zone.options[i].selected= false;
	}
}

function inChanged(option) {
	var changed_rids= document.getElementById("changed_rids");
	var changed = false;
	if(changed_rids.options.length>0){
		for(var i=0;i<changed_rids.options.length;i++){
			if(changed_rids.options[i].value==option.value){
				changed_rids.remove(i);
				changed=true;
			}
		}
	}

	if(!changed){
		var option2= new Option('',option.value);
		changed_rids.options.add(option2);
	}

	return changed;
}

// [BC] 修改confirm按鈕得狀態
function updateConfirmButton(){
	if(document.getElementById("changed_rids").options.length == 0){
		document.getElementById("confirmButton").setAttribute('disabled', 'disabled');
	}else {
		document.getElementById("confirmButton").removeAttribute('disabled');
	}
}

//confirm
function confirm() {
	setSelect(document.getElementById("changed_rids"));
	var arr = document.getElementById("changed_rids").options;
	document.getElementById("confirmForm").submit();
	initialize();
	return false;
}

function setSelect(zone) {
	//alert("in setSelect");
	var length = zone.options.length;
	for(var i = 0;i < length; i++) {
		zone.options[i].selected= true;
	}
}

function initialize(){
	var inList=document.getElementById("inList");
	var notInList=document.getElementById("notInList");
	var changed_rids= document.getElementById("changed_rids");
	changed_rids.options.length=0;
	
	var length = inList.options.length - 1;
	for(var i = length;i >= 0; i--) {
		inList.options[i].selected= false;
		inList.options[i].style.background="white";
	}
	var length = notInList.options.length - 1;
	for(var i = length;i >= 0; i--) {
		notInList.options[i].selected= false;
		notInList.options[i].style.background="white";
	}
}
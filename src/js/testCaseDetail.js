//切換視窗
function switchBox(box) {
    back();
    switch(box)
    {
        case 1:
            switchColor(document.getElementById("tag1"));
            document.getElementById("detailBox").style.visibility = "visible";
            break;
        case 2:
            switchColor(document.getElementById("tag2"));
            document.getElementById("dataBox").style.visibility = "visible";
            break;
        case 3:
            switchColor(document.getElementById("tag3"));
            document.getElementById("relationBox").style.visibility = "visible";
            break;

    }
 }

//切換顏色
function switchColor(obj)
{   
    document.getElementById("tag1").style.backgroundColor ="rgb(40, 40, 40)";
    document.getElementById("tag2").style.backgroundColor ="rgb(40, 40, 40)";
    document.getElementById("tag3").style.backgroundColor ="rgb(40, 40, 40)";
    obj.style.backgroundColor ="grey";
}
//關閉所有小視窗
function back() {
    document.getElementById("detailBox").style.visibility = "hidden";
    document.getElementById("dataBox").style.visibility = "hidden";
    document.getElementById("relationBox").style.visibility = "hidden";
}

//[KL]confirm 
function doConfirm(tid,rid)
{
    var xmlhttp = new XMLHttpRequest();
    var url = "../TestCase/confirmTestCaseRelation.php";

    xmlhttp.open("POST", url, true);
    xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded")
    xmlhttp.send("tid="+tid+"&rid="+rid);

    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            refresh();
        }
    }
    
    
}

//[KL]remove
function doRemove(tid,rid)
{
    var xmlhttp = new XMLHttpRequest();
    var url = "../TestCase/removeTestCaseRelation.php?tid="+tid+"&rid="+rid;

    xmlhttp.open("POST", url, true);
    xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded")
    xmlhttp.send("tid="+tid+"&rid="+rid);
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {       
            refresh();
        }
    }
    
    
}

//[KL]刷新顯示relation的iframe
function refresh()
{
    location.reload()
}
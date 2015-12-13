

var changed_rids = document.getElementById("changed_rids");

//頁面載入完後ajax取得testcase
var xmlhttp = new XMLHttpRequest();
var url = "getTestCase.php";

xmlhttp.open("GET", url, true);
xmlhttp.send();

xmlhttp.onreadystatechange = function() {
    if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
        var arr = JSON.parse(xmlhttp.responseText);
        var testcase = arr.testcases;
        testcase = Object.keys(testcase).map(function(k) {
            return testcase[k];
        });

            var i;
             for (i = 0; i < testcase.length; i++) {
                
                var option=new Option(testcase[i].name,testcase[i].tid);
                option.style.color='black';
                document.getElementById("testcase").options.add(option);
        }
    }
}

function getData(tid){
        alert(tid);
    showRelationWindow();
    //ajax
    var xmlhttp2 = new XMLHttpRequest();
    var url2 = "getTestCaseRelation.php?tid="+tid+"&pid="+document.getElementById("pid").value;

    xmlhttp2.open("GET", url2, true);
    xmlhttp2.send();
    insertInList(xmlhttp2.responseText);
     alert(xmlhttp2.responseText);

    var xmlhttp3 = new XMLHttpRequest();
    var url3 = "getTestCaseNoRelation.php?tid="+tid+"&pid="+document.getElementById("pid").value;

    xmlhttp3.open("GET", url3, true);
    xmlhttp3.send();
    insertNotInList(xmlhttp3.responseText);
  
}

function insertInList(response) {
        alert(response);
    if(inList!=null)
    document.getElementById("inList").options.length=0;
    if(response){

    var arr = JSON.parse(response);
            if (arr.inLists) {
            var inList = arr.inLists;
            inList = Object.keys(inList).map(function(k) {
                return inList[k];
            });
            var i;
            if(inList.length>0)
             for (i = 0; i < inList.length; i++) {
                var option=new Option(inList[i].name,inList[i].rid);
                option.style.color='black';
                document.getElementById("inList").options.add(option);
            }
        }
    }
}

function insertNotInList(response) {
        alert(response);

    if(notInList!=null)
    document.getElementById("notInList").options.length=0;
    if(response){
  var arr = JSON.parse(response);
            if (arr.notInLists) {
                var notInList = arr.notInLists;
                notInList = Object.keys(notInList).map(function(k) {
                    return notInList[k];
                });
                var i;
                if(notInList.length>0)
                 for (i = 0; i < notInList.length; i++) {
                    var option=new Option(notInList[i].name,notInList[i].rid);
                    option.style.color='black';
                   document.getElementById("notInList").options.add(option);
                    }
            }
        }
}

//顯示右方的視窗，並清空changed_rids
function showRelationWindow() {
    document.getElementById("rightBlock").style.visibility = "visible";
    if(changed_rids!=null)
    changed_rids.options.length=0;
}

//confirm
function confirm() {
    setSelect(changed_rids);
    document.getElementById("confirmForm").submit();
    return false;
}



function doAdd() {
    var inList=document.getElementById("inList");
    var notInList=document.getElementById("notInList");
    if(inList.options.selected.length>0)
    {
        for(var i=0;i<inList.options.selected.length;i++)
        {
            inChanged.options.add(Option(inList.options.selected[i]));
            var newOption = notInList.options.add(Option(inList.options.selected[i]));
            newOption.style.background="red";
            inList.remove(inList.options.selected[i].index);
        }
    }
}


function doRemove() {
    var inList=document.getElementById("inList");
    var notInList=document.getElementById("notInList");
        if(notInList.options.selected.length>0)
    {
        for(var i=0;i<notInList.options.selected.length;i++)
        {
            inChanged.options.add(Option(notInList.options.selected[i]));
            var newOption =inList.options.add(Option(notInList.options.selected[i]));
            newOption.style.background="red";
            notInList.remove(notInList.options.selected[i].index);
        }
    }
}

function inChanged(option)
{
    var changed = false;
    if(changed_rids.options.length>0)
    {
        for(var i=0;i<changed_rids.options.length;i++)
        {
            if(changed_rids.options[i].value==option.value)
            {
                changed_rids.remove(i);
                changed=true;
            }
        }
    }

    if(changed==false)
       changed_rids.options.add(Option(option));
}


function setSelect(zone) {
    var length = zone.options.length - 1;
    for(var i = length;i >= 0; i--)
    {
            zone.options[i].selected= true;
    }
}


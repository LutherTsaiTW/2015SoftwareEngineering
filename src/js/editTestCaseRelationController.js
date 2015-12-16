var bool = false;



function getData(tid) {
    document.getElementById("tid").value = tid;
    showRelationWindow();
    //ajax
    var xmlhttp2 = new XMLHttpRequest();
    var url2 = "getTestCaseRelation.php?tid=" + tid + "&pid=" + document.getElementById("pid").value;

    xmlhttp2.open("GET", url2, false);
    xmlhttp2.send();

    insertInList(xmlhttp2.responseText);


    var xmlhttp3 = new XMLHttpRequest();
    var url3 = "getTestCaseNoRelation.php?tid=" + tid + "&pid=" + document.getElementById("pid").value;

    xmlhttp3.open("GET", url3, false);
    xmlhttp3.send();
    insertNotInList(xmlhttp3.responseText);

}

function insertInList(response) {

    document.getElementById("inList").options.length = 0;
    if (response) {

        var arr = $.parseJSON(response);
        if (arr.inLists) {
            var inList = arr.inLists;
            inList = Object.keys(inList).map(function(k) {
                return inList[k];
            });
            var i;
            if (inList.length > 0)
                for (i = 0; i < inList.length; i++) {
                    var option = new Option(inList[i].name, inList[i].rid);
                    option.style.color = 'black';
                    document.getElementById("inList").options.add(option);
                }
        }
    }
}

function insertNotInList(response) {

    document.getElementById("notInList").options.length = 0;
    if (response) {
        var arr = $.parseJSON(response);
        if (arr.notInLists) {
            var notInList = arr.notInLists;
            notInList = Object.keys(notInList).map(function(k) {
                return notInList[k];
            });
            var i;
            if (notInList.length > 0)
                for (i = 0; i < notInList.length; i++) {
                    var option = new Option(notInList[i].name, notInList[i].rid);
                    option.style.color = 'black';
                    document.getElementById("notInList").options.add(option);
                }
        }
    }
}

//顯示右方的視窗，並清空changed_rids
function showRelationWindow() {
    document.getElementById("rightBlock").style.visibility = "visible";
    if (document.getElementById("changed_rids") != null)
        document.getElementById("changed_rids").options.length = 0;
}

//confirm
function confirm() {

    setSelect(document.getElementById("changed_rids"));
    document.getElementById("confirmForm").submit();
    initialize();
    return false;
}

function doRemove() {
    var inList = document.getElementById("inList");
    var notInList = document.getElementById("notInList");

    var length = inList.options.length - 1;
    if (inList.options.length > 0)
        for (var i = length; i >= 0; i--) {
            if (inList.options[i].selected) {
                var newOption = inList.options[i];
                if (!inChanged(newOption)) {
                    newOption.style.background = "red";
                    newOption.style.color = "white";
                } else {
                    newOption.style.background = "white";
                    newOption.style.color = "black";
                }
                notInList.options.add(newOption);
            }
        }

    setNotSelect(inList);
    setNotSelect(notInList);
}


function doAdd() {
    var inList = document.getElementById("inList");
    var notInList = document.getElementById("notInList");

    var length = notInList.options.length - 1;
    if (notInList.options.length > 0)
        for (var i = length; i >= 0; i--) {
            if (notInList.options[i].selected) {
                var newOption = notInList.options[i];
                if (!inChanged(newOption)) {
                    newOption.style.background = "red";
                    newOption.style.color = "white";
                } else {
                    newOption.style.background = "white";
                    newOption.style.color = "black";
                }
                inList.options.add(newOption);
            }
        }

    setNotSelect(inList);
    setNotSelect(notInList);
}

function inChanged(option) {
    var changed_rids = document.getElementById("changed_rids");
    var changed = false;
    if (changed_rids.options.length > 0) {
        for (var i = 0; i < changed_rids.options.length; i++) {
            if (changed_rids.options[i].value == option.value) {
                changed_rids.remove(i);
                changed = true;
            }
        }
    }

    if (!changed) {

        var option2 = new Option('', option.value);
        changed_rids.options.add(option2);
    }

    return changed;
}


function setSelect(zone) {
    var length = zone.options.length - 1;
    for (var i = length; i >= 0; i--) {
        zone.options[i].selected = true;
    }
}

function setNotSelect(zone) {
    var length = zone.options.length - 1;
    for (var i = length; i >= 0; i--) {
        zone.options[i].selected = false;
    }
}

function initialize() {
    var inList = document.getElementById("inList");
    var notInList = document.getElementById("notInList");
    var changed_rids = document.getElementById("changed_rids");
    changed_rids.options.length = 0;
    var length = inList.options.length - 1;
    for (var i = length; i >= 0; i--) {
        inList.options[i].selected = false;
        inList.options[i].style.background = "white";
        inList.options[i].style.color = "black";
    }
    var length = notInList.options.length - 1;
    for (var i = length; i >= 0; i--) {
        notInList.options[i].selected = false;
        notInList.options[i].style.background = "white";
        notInList.options[i].style.color = "black";
    }
}

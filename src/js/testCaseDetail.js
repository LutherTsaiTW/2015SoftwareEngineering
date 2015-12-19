//切換視窗
function switchBox(box) {
    back();
    switch(box)
    {
        case 1:
            document.getElementById("detailBox").style.visibility = "visible";
            break;
        case 2:
            document.getElementById("dataBox").style.visibility = "visible";
            break;
        case 3:
            document.getElementById("relationBox").style.visibility = "visible";
            break;

    }
 }

//關閉所有小視窗
function back() {
    document.getElementById("detailBox").style.visibility = "hidden";
    document.getElementById("dataBox").style.visibility = "hidden";
    document.getElementById("relationBox").style.visibility = "hidden";
}

function doConfirm(tid,rid)
{
    refresh();
}

function doRemove(tid,rid)
{
    refresh();
}

function refresh()
{
    location.reload()
}
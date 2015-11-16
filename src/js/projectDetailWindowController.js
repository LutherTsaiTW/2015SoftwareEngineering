

function showAddMemberWindow() {
    document.getElementById("addMemberWindow").style.visibility = "visible";
    document.getElementById("addMemberWindow").style.top = document.documentElement.clientHeight / 2 - 200;
    document.getElementById("addMemberWindow").style.left = document.documentElement.clientWidth / 2 - 150;
    document.getElementById("block").style.visibility = "visible";

}

function showSuccessWindow() {
    back();
    document.getElementById("successWindow").style.visibility = "visible";
    document.getElementById("successWindow").style.top = document.documentElement.clientHeight / 2 - 75;
    document.getElementById("successWindow").style.left = document.documentElement.clientWidth / 2 - 200;
    document.getElementById("block").style.visibility = "visible";
}

function back() {
    document.getElementById("block").style.visibility = "hidden";
    document.getElementById("addMemberWindow").style.visibility = "hidden";
    document.getElementById("successWindow").style.visibility = "hidden";
}

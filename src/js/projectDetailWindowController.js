
//顯示新增member的視窗
function showAddMemberWindow() {
    document.getElementById("addMemberWindow").style.visibility = "visible";

    document.getElementById("addMemberWindow").style.top = document.documentElement.clientHeight / 2 - 200;
    document.getElementById("addMemberWindow").style.left = document.documentElement.clientWidth / 2 - 150;
    document.getElementById("block").style.visibility = "visible";

}

//顯示新增member成功的視窗
function showSuccessWindow() {
    back();
    document.getElementById("successWindow").style.visibility = "visible";
    document.getElementById("successWindow").style.top = document.documentElement.clientHeight / 2 - 75;
    document.getElementById("successWindow").style.left = document.documentElement.clientWidth / 2 - 200;
    document.getElementById("block").style.visibility = "visible";
}

//關閉所有小視窗
function back() {
    document.getElementById("block").style.visibility = "hidden";
    document.getElementById("addMemberWindow").style.visibility = "hidden";
    document.getElementById("successWindow").style.visibility = "hidden";
    initializeMember();
}

//控制新增member選項
function showAddMemberElements(){
    var addZone = document.getElementById("addZone");

   
}
    var addZone;
    var iniAddZone;


function getMemberData()
{
     addZone=document.getElementById("addZone");
     removeZone=document.getElementById("removeZone");
 
}

function addMember()
{
    var  length  =  addZone.options.length  -   1 ;    
    for(var i=length;i>=0;i--){
        if(addZone.options[i].selected){
              var  varItem  =   addZone.options[i];      
             removeZone.options.add(varItem);                    
         }
    }
}

function removeMember()
{
    var  length  =  removeZone.options.length  -   1 ;    
    for(var i=length;i>=0;i--){
        if(removeZone.options[i].selected){
              var  varItem  =   removeZone.options[i];      
             addZone.options.add(varItem);                    
         }
    }
}

function initializeMember()
{
   document.getElementById("addZone").innerHTML=document.getElementById("iniAddZone").innerHTML;
    document.getElementById("removeZone").innerHTML=document.getElementById("iniRemoveZone").innerHTML;
}
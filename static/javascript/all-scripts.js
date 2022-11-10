function sign_up(){

    document.getElementById("form").innerHTML = "Loading...";

    obj = new XMLHttpRequest;
    obj.onreadystatechange = function(){
        if(obj.readyState == 4){
            document.getElementById("form").innerHTML = obj.responseText;
        }
    }
    
    obj.open("GET","/ajax/signup.php");
    obj.send(null);
}

function login(){

    document.getElementById("form").innerHTML = "Loading...";

    obj = new XMLHttpRequest;
    obj.onreadystatechange = function(){
        if(obj.readyState == 4){
            document.getElementById("form").innerHTML = obj.responseText;
        }
    }
    
    obj.open("GET","/ajax/login.php");
    obj.send(null);
}

function check_password(){
    if (document.getElementById("password").value.length < 8) {
        document.getElementById("password-model").style = "color:red"; 
    } else {
        document.getElementById("password-model").style = "color:#000";
    }
}

function password_confirm() {
    if (document.getElementById("password-confirm").value.length >= 3) {
        if (document.getElementById("password").value !== document.getElementById("password-confirm").value) {
            document.getElementById("password-confirm-model").style = "display:block";
            document.getElementById("password-confirm-model").style = "color:red";
        } else {
            document.getElementById("password-confirm-model").style = "display:none";
        }
    }
}

const collection = document.getElementsByClassName("invalid");

for (let i=0; i < collection.length; i++){
    collection[i].style = "display:block";
    //collection[i].style = "color:pink";
    
    var innerHT = collection[i].innerHTML;

    var newInnerHT = innerHT + "<span style='float:right;margin:4px 18px'><i class='fa fa-times' onclick='show_class_div()'></i></span>";

    collection[i].innerHTML = newInnerHT;
}

function show_class_div(){
    const collection = document.getElementsByClassName("invalid");
    i = 0;

    for (i=0; i < collection.length; i++) {
        collection[i].style.display = "none";
    }
}

function show_div(vari) {
    if (document.getElementById(vari).style.display == "none") {
        document.getElementById(vari).style.display = "block";
    } else if (document.getElementById(vari).style.display == "block") {
        document.getElementById(vari).style.display = "none";
    }
}

function ajax_dashboard(section){

    document.getElementById("inner-dashboard-main").innerHTML = "Loading...";

    obj = new XMLHttpRequest;
    obj.onreadystatechange = function(){
        if(obj.readyState == 4){
            document.getElementById("inner-dashboard-main").innerHTML = obj.responseText;
        }
    }
    
    if (section == "new-poll") {
        obj.open("GET","/ajax/new-poll.php");
        obj.send(null);
    }
}

function poll_options(user_id, poll_id){

    document.getElementById("inner-dashboard-main").innerHTML = "Loading...";

    obj = new XMLHttpRequest;
    obj.onreadystatechange = function(){
        if(obj.readyState == 4){
            document.getElementById("inner-dashboard-main").innerHTML = obj.responseText;
        }
    }
    
    obj.open("GET","/ajax/poll_options.php?user="+user_id+"&poll="+poll_id);
    obj.send(null);
}
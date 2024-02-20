let selectedID = -1;

function toggle_point_panel(){
    if(document.getElementById("driver_id_holder").value != -1){
        if(document.getElementById("manage_driver_panel").style.display == "none"){
        document.getElementById("manage_driver_panel").setAttribute("style", "display: absolute;");
        }
    }
    else{
        document.getElementById("manage_driver_panel").style.display = "none";
    }
}

function driver_select(){
    document.forms["select_driver_form"].submit();
}

function manage_driver(){
    if(document.getElementById("driver_id_holder2").value != -1){
        document.getElementById('manage_driver_form').setAttribute("action", "manage_user.php");
        document.forms["manage_driver_form"].submit();
    }
}

function view_hist(){
    if(document.getElementById("driver_id_holder2").value != -1){
        document.getElementById('manage_driver_form').setAttribute("action", "view_driver_history.php");
        document.forms["manage_driver_form"].submit();
    }
}
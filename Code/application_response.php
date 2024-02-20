<?php
require_once "Database/config.php";
header('Location: home_page.php');
if($_SERVER['REQUEST_METHOD'] == "POST"){
    $app_id = $_POST['app_id'];
    $choice = $_POST['choice'];
    $query = " ";
    if($choice == "1"){
        $query = "call accept_application($app_id);";
    }
    elseif ($choice == "0") {
        $query = "call reject_application($app_id);";
    }
    $result = mysqli_query($con, $query);
}

?>
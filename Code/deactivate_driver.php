<?php
require_once "Database/config.php";

if(($_SESSION['role'] != 'admin' AND $_SESSION['role'] != 'sponsor') OR $_SERVER['REQUEST_METHOD'] != "POST"){
    header("location: home_page.php");
}

if($_SERVER['REQUEST_METHOD'] == "POST"){
    mysqli_query($con, "call deactivate_driver({$_POST['user_id']}, {$_SESSION['id']}, {$_SESSION['org_id']})");
    echo("call deactivate_driver({$_POST['user_id']}, {$_SESSION['id']}, {$_SESSION['org_id']})");
    header("location: home_page.php");
}

?>
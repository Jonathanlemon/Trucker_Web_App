<?php
require_once("Database/config.php");

if($_SESSION["role"] == "admin" && $_SERVER["REQUEST_METHOD"] == "POST"){
    mysqli_query($con, "UPDATE users set active = 1 where user_id = {$_POST['user_id']}");
    $target_email = mysqli_query($con, "SELECT email from users where user_id = {$_POST['user_id']}")->fetch_assoc()['email'];
    $current_email = mysqli_query($con, "SELECT email from users where user_id = {$_SESSION['id']}")->fetch_assoc()['email'];
    mysqli_query($con, "call manual_audit(\"Enabled Account\", \"USER: {$target_email} was enabled by ADMIN: {$current_email}\")");
}

header("location: home_page.php");

?>
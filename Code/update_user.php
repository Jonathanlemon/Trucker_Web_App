<?php
require_once "Database/config.php";

if(isset($_POST['managed_call']) && ($_SESSION['role'] == 'admin' OR $_SESSION['role'] == 'sponsor')){
    $target_email = $_POST['txtEmail'];
    $f_name = trim($_POST['txtFirstName']);
    $l_name = trim($_POST['txtLastName']);
    $s_a1 = trim($_POST['txtAnswer1']);
    $s_a2 = trim($_POST['txtAnswer2']);
    $s_a3 = trim($_POST['txtAnswer3']);
    $pword = trim($_POST['password']);
    if(strlen($pword) > 0){
       $pword = password_hash($pword, PASSWORD_DEFAULT);
       mysqli_query($con, "UPDATE users SET first_name='$f_name', last_name='$l_name', security_answer1='$s_a1', security_answer2='$s_a2', security_answer3='$s_a3', password='$pword' where email = '$target_email'");
       mysqli_query($con, "CALL manual_audit('Profile Update', 'User {$_SESSION['email']} updated the profile information for user {$target_email}')");
    }
    else{
       mysqli_query($con, "UPDATE users SET first_name='$f_name', last_name='$l_name', security_answer1='$s_a1', security_answer2='$s_a2', security_answer3='$s_a3' where email = '$target_email'");
       mysqli_query($con, "CALL manual_audit('Profile Update', 'User {$_SESSION['email']} updated the profile information for user {$target_email}')");
    }
    header("location: manage_user.php");
 }

 else{
    header("location: home_page.php");
 }

?>
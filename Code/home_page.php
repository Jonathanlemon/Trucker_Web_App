<?php

require_once "Database/config.php";

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
}

if($_SESSION['role'] === 'driver'){
    include "driver_home_page.php";
}
if($_SESSION['role'] === 'admin'){
    include "admin_home_page.php";
}
if($_SESSION['role'] === 'sponsor'){
    include "sponsor_home_page.php";
}

?>
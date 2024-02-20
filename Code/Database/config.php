<?php
session_start();
$host = "team4-database.cobd8enwsupz.us-east-1.rds.amazonaws.com"; /* Host name */
$user = "admin"; /* User */
$password = "CPSC4910_T3AM04"; /* Password */
$dbname = "4910_team4_database"; /* Database name */

$con = mysqli_connect($host, $user, $password,$dbname);
// Check connection
if (!$con) {
  die("Failed to connect to database: " . mysqli_connect_error());
}
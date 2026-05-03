<?php

$servername = "sql110.infinityfree.com";
$username = "if0_41804842";
$password = "Super-seccret-password";
$dbname = "if0_41804842_sport";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if(!$conn){
die("Connection Failed");
}


?>
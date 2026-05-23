<?php
$host = "localhost";
$user = "root";       // default XAMPP user
$pass = "";           // default XAMPP password is empty
$dbname = "cms"; // your database name

$conn = mysqli_connect($host, $user, $pass, $dbname);

if(!$conn){
    die("Connection failed: " . mysqli_connect_error());
}
?>

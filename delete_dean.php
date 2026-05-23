<?php
session_start();
include "Connection.php";

if (!isset($_SESSION['admin_id'])) exit();

$id = $_GET['id'];

mysqli_query($conn, "DELETE FROM dean_login WHERE id='$id'");

header("Location: manage_deans.php");

<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cms";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$fullname = $_POST['fullname'];
$email = $_POST['email'];
$subject = $_POST['subject'];
$message = $_POST['message'];

$sql = "INSERT INTO contact_table (fullname, email, subject, message)
        VALUES ('$fullname', '$email', '$subject', '$message')";

if ($conn->query($sql) === TRUE) {
    echo "<script>
            alert('Your message has been sent successfully!');
            window.location.href='ContactUs.php';
          </script>";
} else {
    echo "<script>
            alert('Failed to send message. Try again.');
            window.location.href='ContactUs.php';
          </script>";
}

$conn->close();
?>

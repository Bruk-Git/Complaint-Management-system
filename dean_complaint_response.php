<?php
session_start();
include "Connection.php";

if (!isset($_SESSION['dean_id'])) {
    header("Location: dean_login.php");
    exit();
}

$dean_name = $_SESSION['dean_name'];

if (!isset($_GET['id'])) {
    die("Complaint not found");
}

$complaint_id = intval($_GET['id']);

// Fetch complaint
$complaint = mysqli_query(
    $conn,
    "SELECT * FROM complaints WHERE id = $complaint_id"
);

$data = mysqli_fetch_assoc($complaint);
if (!$data) {
    die("Invalid Complaint");
}

// Handle response submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $response = mysqli_real_escape_string($conn, $_POST['response_text']);

    // Save response
    mysqli_query($conn, "
        INSERT INTO responses 
        (complaint_id, responder_role, responder_name, response_text, status)
        VALUES 
        ('$complaint_id', 'Dean Office', '$dean_name', '$response', 'Resolved')
    ");

    // Update complaint status
    mysqli_query($conn, "
        UPDATE complaints 
        SET status='Resolved' 
        WHERE id='$complaint_id'
    ");

    echo "<script>
            alert('Complaint resolved and response sent to student.');
            window.location.href='dean_dashboard.php';
          </script>";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="icon" href="images/Logos/AU Logo.png">
<title>View Complaint</title>

<style>
body {
    font-family: Arial, sans-serif;
    background: #eef3f7;
    padding: 20px;
}
.box {
    background: white;
    padding: 20px;
    border-radius: 8px;
    max-width: 800px;
    margin: auto;
}
h2 {
    color: #002b55;
}
.detail {
    margin-bottom: 12px;
}
label {
    font-weight: bold;
}
textarea {
    width: 100%;
    height: 140px;
    padding: 10px;
}
.btn {
    background: #28a745;
    color: white;
    padding: 10px 18px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}
.back {
    display: inline-block;
    margin-top: 10px;
    color: #002b55;
    text-decoration: none;
}
img {
    max-width: 100%;
    margin-top: 10px;
}
</style>
</head>

<body>

<div class="box">

<h2>Complaint Details</h2>

<div class="detail"><label>Student:</label> <?php echo $data['student_name']; ?> (<?php echo $data['student_id']; ?>)</div>
<div class="detail"><label>Department:</label> <?php echo $data['department']; ?></div>
<div class="detail"><label>Subject:</label> <?php echo $data['subject']; ?></div>
<div class="detail"><label>Message:</label><br><?php echo nl2br($data['message']); ?></div>

<?php if (!empty($data['image'])) { ?>
    <div class="detail">
        <label>Attached Image:</label><br>
        <img src="complaint_images/<?php echo $data['image']; ?>">
    </div>
<?php } ?>

<hr>

<h3>Dean Response</h3>

<form method="POST">
    <textarea name="response_text" required placeholder="Write your decision / response..."></textarea>
    <br><br>
    <button class="btn">Resolve Complaint</button>
</form>

<a class="back" href="dean_dashboard.php">← Back to Dashboard</a>

</div>

</body>
</html>

<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin Settings | CMS</title>
<link rel="stylesheet" href="admin.css">
</head>
<body>

<?php include "admin_sidebar.php"; ?>

<div class="main">
    <h2>Settings</h2>

    <form method="post" action="save_settings.php">

        <h4>System Settings</h4>
        <label>System Name</label>
        <input type="text" name="system_name">

        <label>Timezone</label>
        <select name="timezone">
            <option value="Africa/Addis_Ababa">Africa/Addis_Ababa</option>
        </select>

        <h4>Complaint Settings</h4>
        <label>Max Upload Size (MB)</label>
        <input type="number" name="max_upload" value="2">

        <label>Allow Anonymous Complaints</label>
        <select name="anonymous">
            <option value="yes">Yes</option>
            <option value="no">No</option>
        </select>

        <h4>Notifications</h4>
        <label>
            <input type="checkbox" name="notify_student"> Notify student on response
        </label>

        <br><br>
        <button type="submit">Save Settings</button>
    </form>
</div>

</body>
</html>

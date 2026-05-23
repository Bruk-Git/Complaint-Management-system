<?php
session_start();
include "Connection.php";

if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit();
}

$student_id = $_SESSION['student_id'];

$sql = "
SELECT 
    c.complaint_id,
    c.subject,
    c.status AS complaint_status,
    c.response_text,
    c.response_file,
    c.response_date,
    c.responder_name,
    c.teacher_name,
    c.dean_name
FROM complain c
WHERE c.student_id = '$student_id'
ORDER BY c.created_at DESC
";

$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Query error: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="icon" href="images/Logos/AU Logo.png">
<title>Complaint Status | Admas University</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

body {
    background: linear-gradient(135deg, #f5f7fa 0%, #e4edf5 100%);
    min-height: 100vh;
    padding: 30px;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.log-img {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    border: 4px solid #003b6f;
    padding: 5px;
    background: white;
    box-shadow: 0 8px 25px rgba(0, 59, 111, 0.2);
    margin-bottom: 20px;
}

h2 {
    color: #003b6f;
    font-size: 32px;
    font-weight: 700;
    margin-bottom: 30px;
    text-align: center;
    position: relative;
    padding-bottom: 15px;
}

h2::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 100px;
    height: 4px;
    background: linear-gradient(90deg, #003b6f, #ffc107);
    border-radius: 2px;
}

.container {
    background: white;
    padding: 35px;
    border-radius: 16px;
    box-shadow: 0 15px 40px rgba(0, 59, 111, 0.15);
    width: 100%;
    max-width: 1200px;
    border: 1px solid rgba(0, 0, 0, 0.05);
}

/* Table Styles */
table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
}

thead {
    background: linear-gradient(135deg, #003b6f 0%, #005fa3 100%);
}

th {
    padding: 18px 20px;
    color: #003b6f;
    font-weight: 600;
    text-align: left;
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-bottom: none;
}

th:first-child {
    border-top-left-radius: 12px;
}

th:last-child {
    border-top-right-radius: 12px;
}

tbody tr {
    transition: all 0.3s ease;
    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
}

tbody tr:hover {
    background: #f8fafd;
    transform: translateX(5px);
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
}

td {
    padding: 20px;
    color: #555;
    font-size: 14px;
    font-weight: 500;
    vertical-align: top;
    background: white;
}

/* Status Badges */
.status {
    display: inline-block;
    padding: 10px 18px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.3px;
    text-align: center;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
    min-width: 150px;
}

.status:hover {
    transform: scale(1.05);
    box-shadow: 0 6px 18px rgba(0, 0, 0, 0.15);
}

.status.Pending { 
    background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
    color: white;
}
.status.OnReview { 
    background: linear-gradient(135deg, #17a2b8 0%, #0dcaf0 100%);
    color: white;
}
.status.AssignedtoTeacher { 
    background: linear-gradient(135deg, #0078d4 0%, #005fa3 100%);
    color: white;
}
.status.AssignedtoDean { 
    background: linear-gradient(135deg, #ff9800 0%, #ffc107 100%);
    color: #003b6f;
}
.status.Responded { 
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
}
.status.Resolved { 
    background: linear-gradient(135deg, #2ecc71 0%, #27ae60 100%);
    color: white;
}
/* Buttons */
.btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 24px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    color: white;
    text-decoration: none;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
    position: relative;
    overflow: hidden;
}
.btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, 
        transparent, 
        rgba(255, 255, 255, 0.2), 
        transparent);
    transition: left 0.7s;
}
.btn:hover::before {
    left: 100%;
}
.btn.view {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
}
.btn.view:hover {
    background: linear-gradient(135deg, #20c997 0%, #28a745 100%);
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(40, 167, 69, 0.3);
}
.btn.download {
    background: linear-gradient(135deg, #6f42c1 0%, #8a63d2 100%);
}
.btn.download:hover {
    background: linear-gradient(135deg, #8a63d2 0%, #6f42c1 100%);
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(111, 66, 193, 0.3);
}

/* Response Details Styling */
td strong {
    color: #003b6f;
    font-size: 14px;
    display: block;
    margin-bottom: 5px;
}

td small {
    color: #666;
    font-size: 12px;
    font-style: italic;
}

td i {
    color: #888;
    font-style: italic;
}
/* Layout specific for response column */
td:nth-child(4) {
    min-width: 300px;
}
/* Status column */
td:nth-child(3) {
    text-align: center;
}
/* Empty state styling */
td:contains("—") {
    color: #888;
    font-style: italic;
    text-align: center;
}
/* Animation for table rows */
@keyframes fadeInRow {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
tbody tr {
    animation: fadeInRow 0.5s ease-out forwards;
}
tbody tr:nth-child(1) { animation-delay: 0.1s; }
tbody tr:nth-child(2) { animation-delay: 0.2s; }
tbody tr:nth-child(3) { animation-delay: 0.3s; }
tbody tr:nth-child(4) { animation-delay: 0.4s; }
tbody tr:nth-child(5) { animation-delay: 0.5s; }

/* Responsive Design */
@media (max-width: 1024px) {
    .container {
        padding: 25px;
    }
    
    table {
        display: block;
        overflow-x: auto;
    }
    
    th, td {
        padding: 15px;
    }
}

@media (max-width: 768px) {
    body {
        padding: 20px;
    }
    
    .log-img {
        width: 80px;
        height: 80px;
    }
    
    h2 {
        font-size: 26px;
    }
    
    .container {
        padding: 20px;
    }
    
    .status {
        padding: 8px 12px;
        font-size: 11px;
        min-width: 120px;
    }
    
    .btn {
        padding: 10px 18px;
        font-size: 13px;
    }
    
    td:nth-child(4) {
        min-width: 250px;
    }
}

@media (max-width: 480px) {
    body {
        padding: 15px;
    }
    
    .log-img {
        width: 70px;
        height: 70px;
    }
    
    h2 {
        font-size: 22px;
    }
    
    .container {
        padding: 15px;
    }
    
    th, td {
        padding: 12px 8px;
        font-size: 13px;
    }
    
    .status {
        padding: 6px 10px;
        font-size: 10px;
        min-width: 100px;
    }
    
    .btn {
        padding: 8px 14px;
        font-size: 12px;
    }
}

/* Scrollbar Styling */
::-webkit-scrollbar {
    width: 8px;
    height: 8px;
}

::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
}

::-webkit-scrollbar-thumb {
    background: linear-gradient(135deg, #003b6f 0%, #005fa3 100%);
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(135deg, #005fa3 0%, #003b6f 100%);
}

/* Back Button (Optional - add at top of HTML) */
.back-btn {
    position: absolute;
    top: 30px;
    left: 30px;
    background: linear-gradient(135deg, #003b6f 0%, #005fa3 100%);
    color: white;
    padding: 12px 24px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s ease;
}
.back-btn:hover {
    background: linear-gradient(135deg, #005fa3 0%, #007acc 100%);
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(0, 95, 163, 0.3);
}
</style>
</head>
<body>
<div class="container">
    <img class="log-img" src="images/logos/AU Logo.png" alt="AU Logo">
    <h2>Your Complaint Status</h2>

    <table>
        <tr>
            <th>ID</th>
            <th>Subject</th>
            <th>Status</th>
            <th>Response Details</th>
            <th>Files</th>
        </tr>

        <?php while ($row = mysqli_fetch_assoc($result)):

            $status = $row['complaint_status']; // ✅ correct column
            $statusClass = strtolower(str_replace(' ', '-', $status));
        ?>
        <tr>
            <td>#<?= $row['complaint_id']; ?></td>

            <td><?= htmlspecialchars($row['subject']); ?></td>

            <td class="status <?= $statusClass; ?>">
                <?= htmlspecialchars($status); ?>
            </td>

            <td>
                <?php if (!empty($row['response_text'])): ?>

                    <strong>Responded By:</strong><br>
                    <?= htmlspecialchars($row['responder_name']); ?><br>

                    <?php if (!empty($row['teacher_name'])): ?>
                        <small>(Teacher)</small>
                    <?php elseif (!empty($row['dean_name'])): ?>
                        <small>(Dean Office)</small>
                    <?php else: ?>
                        <small>(Department)</small>
                    <?php endif; ?>

                    <br><br>

                    <strong>Date:</strong>
                    <?= date("M d, Y H:i", strtotime($row['response_date'])); ?>

                    <br><br>

                    <a class="btn view"
                       href="view_response.php?id=<?= $row['complaint_id']; ?>">
                        View Full Response
                    </a>

                <?php else: ?>
                    <i>No response yet</i>
                <?php endif; ?>
            </td>

            <td>
                <?php if (!empty($row['response_file'])): ?>
                    <a class="btn download"
                       href="uploads/responses/<?= $row['response_file']; ?>"
                       download>
                        Download
                    </a>
                <?php else: ?>
                    —
                <?php endif; ?>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

</body>
</html>
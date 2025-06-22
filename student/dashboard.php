<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['user_role'] !== 'student') {
    header("Location: ../auth/login.php");
    exit;
}

include("../config/db.php");
$username = $_SESSION['username'];


$stmt = $conn->prepare("SELECT full_name FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$full_name = $user['full_name'] ?? $username;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Student Dashboard - Attendance System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
    body {
        background-color: #f8f9fa;
    }

    .dashboard-card {
        max-width: 700px;
        margin: 40px auto;
        padding: 30px;
        background-color: white;
        border-radius: 10px;
        box-shadow: 0px 0px 12px rgba(0, 0, 0, 0.1);
    }

    .dashboard-card ul {
        list-style-type: none;
        padding-left: 0;
    }

    .dashboard-card li {
        margin-bottom: 12px;
    }

    .dashboard-card a {
        text-decoration: none;
        color: #007bff;
    }

    .dashboard-card a:hover {
        text-decoration: underline;
    }
    </style>
</head>

<body>


    <nav class="navbar navbar-expand-lg navbar-dark bg-success">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Student Panel</a>
            <div class="d-flex">
                <span class="navbar-text text-white me-3">
                    Student: <?= htmlspecialchars($full_name) ?>
                </span>
                <a href="../auth/logout.php" class="btn btn-danger">Logout</a>
            </div>
        </div>
    </nav>


    <div class="dashboard-card">
        <h3 class="text-center mb-4">📊 Student Dashboard</h3>

        <ul>
            <li><a href="my_attendance.php">✅ View My Attendance</a></li>
            <li><a href="monthly_calendar.php">📅 Monthly Attendance Calendar</a></li>
        </ul>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
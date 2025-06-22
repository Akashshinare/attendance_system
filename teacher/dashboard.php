<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['user_role'] !== 'teacher') {
    header("Location: ../auth/login.php");
    exit;
}

$user = $_SESSION['username'];
$role = $_SESSION['user_role'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Teacher Dashboard - Student Attendance System</title>
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


    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Student Attendance</a>
            <div class="d-flex">
                <span class="navbar-text text-white me-3">
                    Teacher: <?= htmlspecialchars($user) ?>
                </span>
                <a href="../auth/logout.php" class="btn btn-danger">Logout</a>
            </div>
        </div>
    </nav>


    <div class="dashboard-card">
        <h3 class="text-center mb-4">Teacher Dashboard</h3>

        <ul>
            <li><a href="mark_attendance.php">✅ Mark Attendance</a></li>
            <li><a href="view_history.php">📅 View Attendance History</a></li>
        </ul>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
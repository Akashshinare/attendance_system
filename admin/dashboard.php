<?php
session_start();
if (!isset($_SESSION['username'])) {
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
    <title><?= ucfirst($role) ?> Dashboard - Student Attendance System</title>
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
                    <?= ucfirst($role) ?>: <?= htmlspecialchars($user) ?>
                </span>
                <a href="../auth/logout.php" class="btn btn-danger">Logout</a>
            </div>
        </div>
    </nav>


    <div class="dashboard-card">
        <h3 class="text-center mb-4"><?= ucfirst($role) ?> Dashboard</h3>

        <?php if ($role == 'admin') : ?>
        <ul>
            <li><a href="Students.php">➕ Add/Edit Students</a></li>
            <li><a href="manage_subjects.php">📚 Manage Subjects</a></li>
            <li><a href="assign_students.php">👥 Assign Students to Classes</a></li>
            <li><a href="view_attendance.php">📄 View Attendance Reports</a></li>
        </ul>
        <?php elseif ($role == 'teacher') : ?>
        <ul>
            <li><a href="mark_attendance.php">✅ Mark Attendance</a></li>
            <li><a href="view_history">📅 View Attendance History</a></li>
        </ul>
        <?php elseif ($role == 'student') : ?>
        <ul>
            <li><a href="my_attendance.php">📊 View My Attendance</a></li>
            <li><a href="monthly_calender.php">🗓️ Monthly Calendar</a></li>
        </ul>
        <?php endif; ?>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
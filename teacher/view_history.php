<?php
session_start();
include("../config/db.php");


if (!isset($_SESSION['username']) || $_SESSION['user_role'] !== 'teacher') {
    header("Location: ../auth/login.php");
    exit;
}

$teacher = $_SESSION['username'];


$sql = "SELECT a.attendance_date, s.full_name, subj.subject_name, a.status
        FROM attendance a
        JOIN students s ON a.student_id = s.student_id
        JOIN subjects subj ON a.subject_id = subj.subject_id
        ORDER BY a.attendance_date DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Attendance History - Teacher Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h2 class="mb-4 text-center">📅 Attendance History</h2>

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Student Name</th>
                    <th>Subject</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['attendance_date']) ?></td>
                    <td><?= htmlspecialchars($row['full_name']) ?></td>
                    <td><?= htmlspecialchars($row['subject_name']) ?></td>
                    <td><?= ucfirst($row['status']) ?></td>
                </tr>
                <?php endwhile; ?>
                <?php else: ?>
                <tr>
                    <td colspan="4" class="text-center">No attendance records found.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="text-center mt-4">
            <a href="dashboard.php" class="btn btn-secondary">⬅️ Back to Dashboard</a>
        </div>
    </div>
</body>

</html>
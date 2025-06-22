<?php
include("../config/db.php");

$query = "SELECT 
            s.full_name AS student_name,
            sub.subject_name,
            a.status,
            a.attendance_date
          FROM attendance a
          JOIN students s ON a.student_id = s.student_id
          JOIN subjects sub ON a.subject_id = sub.subject_id
          ORDER BY a.attendance_date DESC";

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>All Students Attendance</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">📊 All Students Attendance</a>
            <a href="dashboard.php" class="btn btn-light">⬅ Back to Dashboard</a>
        </div>
    </nav>

    <div class="container mt-5">
        <h3 class="mb-4">🧑‍🎓 Attendance Records</h3>

        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Student Name</th>
                    <th>Subject</th>
                    <th>Status</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['student_name']) ?></td>
                    <td><?= htmlspecialchars($row['subject_name']) ?></td>
                    <td><?= ucfirst($row['status']) ?></td>
                    <td><?= htmlspecialchars($row['attendance_date']) ?></td>
                </tr>
                <?php endwhile; ?>
                <?php else: ?>
                <tr>
                    <td colspan="4" class="text-center">No attendance records found.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
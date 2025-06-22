<?php
include("../config/db.php");
session_start();
if (!isset($_SESSION['username']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}


$students = $conn->query("SELECT student_id, full_name FROM students ORDER BY full_name ASC");


$filter_student = $_GET['student_id'] ?? '';
$filter_date = $_GET['date'] ?? '';

$where = "1";
$params = [];

if ($filter_student) {
    $where .= " AND attendance.student_id = ?";
    $params[] = $filter_student;
}

if ($filter_date) {
    $where .= " AND attendance.attendance_date = ?";
    $params[] = $filter_date;
}

$sql = "SELECT attendance.*, students.full_name, subjects.subject_name
        FROM attendance
        JOIN students ON attendance.student_id = students.student_id
        JOIN subjects ON attendance.subject_id = subjects.subject_id
        WHERE $where
        ORDER BY attendance.attendance_date DESC";

$stmt = $conn->prepare($sql);

if (count($params)) {
    $types = str_repeat("s", count($params));
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>View Attendance Reports</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h2 class="mb-4">📊 Attendance Reports</h2>
        <form method="GET" class="row g-3 mb-4">
            <div class="col-md-4">
                <label class="form-label">Student:</label>
                <select name="student_id" class="form-select">
                    <option value="">All Students</option>
                    <?php while ($student = $students->fetch_assoc()): ?>
                    <option value="<?= $student['student_id'] ?>"
                        <?= $filter_student == $student['student_id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($student['full_name']) ?>
                    </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Date:</label>
                <input type="date" name="date" value="<?= htmlspecialchars($filter_date) ?>" class="form-control">
            </div>
            <div class="col-md-4 align-self-end">
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="view_attendance.php" class="btn btn-secondary">Reset</a>
            </div>
        </form>

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Student</th>
                    <th>Subject</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['attendance_date'] ?></td>
                    <td><?= htmlspecialchars($row['full_name']) ?></td>
                    <td><?= htmlspecialchars($row['subject_name']) ?></td>
                    <td><?= ucfirst($row['status']) ?></td>
                </tr>
                <?php endwhile; ?>
                <?php else: ?>
                <tr>
                    <td colspan="4" class="text-center">No records found.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <a href="dashboard.php" class="btn btn-secondary mt-3">⬅ Back to Dashboard</a>
    </div>
</body>

</html>
<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['username']) || $_SESSION['user_role'] !== 'teacher') {
    header("Location: ../auth/login.php");
    exit;
}

$message = "";

$students = $conn->query("SELECT student_id, full_name FROM students ORDER BY full_name ASC");
$subjects = $conn->query("SELECT subject_id, subject_name FROM subjects ORDER BY subject_name ASC");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = $_POST['student_id'] ?? '';
    $subject_id = $_POST['subject_id'] ?? '';
    $status = $_POST['status'] ?? '';
    $date = $_POST['date'] ?? date('Y-m-d');

    if ($student_id && $subject_id && $status) {
        $stmt = $conn->prepare("INSERT INTO attendance (student_id, subject_id, status, attendance_date) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiss", $student_id, $subject_id, $status, $date);

        if ($stmt->execute()) {
            $message = '<div class="alert alert-success">Attendance marked successfully!</div>';
        } else {
            $message = '<div class="alert alert-danger">Error marking attendance.</div>';
        }
    } else {
        $message = '<div class="alert alert-warning">Please fill in all fields.</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Mark Attendance</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h2 class="mb-4">✅ Mark Attendance</h2>

        <?= $message ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Select Student:</label>
                <select name="student_id" class="form-select" required>
                    <option value="">-- Select Student --</option>
                    <?php while ($row = $students->fetch_assoc()) : ?>
                    <option value="<?= $row['student_id'] ?>"><?= htmlspecialchars($row['full_name']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Select Subject:</label>
                <select name="subject_id" class="form-select" required>
                    <option value="">-- Select Subject --</option>
                    <?php while ($row = $subjects->fetch_assoc()) : ?>
                    <option value="<?= $row['subject_id'] ?>"><?= htmlspecialchars($row['subject_name']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Date:</label>
                <input type="date" name="date" value="<?= date('Y-m-d') ?>" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label">Attendance Status:</label>
                <select name="status" class="form-select" required>
                    <option value="">-- Select Status --</option>
                    <option value="present">Present</option>
                    <option value="absent">Absent</option>
                </select>
            </div>

            <div class="d-flex justify-content-between">
                <a href="dashboard.php" class="btn btn-secondary">⬅ Back to Dashboard</a>
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
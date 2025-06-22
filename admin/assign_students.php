<?php


session_start();
if (!isset($_SESSION['username']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

include("../config/db.php");

$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $subject_id = $_POST['subject_id'];
    $student_id = $_POST['student_id'];

    $check = $conn->prepare("SELECT * FROM subject_student WHERE subject_id = ? AND student_id = ?");
    $check->bind_param("ii", $subject_id, $student_id);
    $check->execute();
    $res = $check->get_result();

    if ($res->num_rows > 0) {
        $message = "<div class='alert alert-warning'>⚠️ Student already assigned to this subject.</div>";
    } else {
        $stmt = $conn->prepare("INSERT INTO subject_student (subject_id, student_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $subject_id, $student_id);
        if ($stmt->execute()) {
            $message = "<div class='alert alert-success'>✅ Student successfully assigned.</div>";
        } else {
            $message = "<div class='alert alert-danger'>❌ Failed to assign student.</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Assign Students to Subjects</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container mt-5">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h4>Assign Student to Subject</h4>
            </div>
            <div class="card-body">
                <?= $message ?>
                <form method="post" action="">
                    <div class="mb-3">
                        <label for="subject_id" class="form-label">Select Subject</label>
                        <select name="subject_id" id="subject_id" class="form-select" required>
                            <option value="">-- Select Subject --</option>
                            <?php
                            $subjects = $conn->query("SELECT * FROM subjects ORDER BY subject_name ASC");
                            while ($sub = $subjects->fetch_assoc()) {
                                echo "<option value='{$sub['subject_id']}'>{$sub['subject_name']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="student_id" class="form-label">Select Student</label>
                        <select name="student_id" id="student_id" class="form-select" required>
                            <option value="">-- Select Student --</option>
                            <?php
                            $students = $conn->query("SELECT * FROM students ORDER BY full_name ASC");
                            while ($stu = $students->fetch_assoc()) {
                                echo "<option value='{$stu['student_id']}'>{$stu['full_name']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-success">Assign Student</button>
                    <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
                </form>
            </div>
        </div>
    </div>
</body>

</html>
<?php
include("../config/db.php");

$subject_query = "SELECT * FROM subjects";
$subject_result = $conn->query($subject_query);

$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $subject_id = $_POST['subject_id'];
    $student_id = $_POST['student_id'];

    
    $conn->query("CREATE TABLE IF NOT EXISTS subject_student (
        id INT AUTO_INCREMENT PRIMARY KEY,
        subject_id INT NOT NULL,
        student_id INT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        UNIQUE(subject_id, student_id)
    )");

    $check = $conn->prepare("SELECT * FROM subject_student WHERE subject_id = ? AND student_id = ?");
    $check->bind_param("ii", $subject_id, $student_id);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        $message = "<p class='text-danger'>❌ Student already assigned to this subject.</p>";
    } else {
        $stmt = $conn->prepare("INSERT INTO subject_student (subject_id, student_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $subject_id, $student_id);
        if ($stmt->execute()) {
            $message = "<p class='text-success'>✅ Student assigned successfully.</p>";
        } else {
            $message = "<p class='text-danger'>❌ Error assigning student.</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Assign Students</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h2 class="text-center">Assign Students to Subjects</h2>
        <?= $message ?>
        <form method="post">
            <div class="mb-3">
                <label>Select Subject:</label>
                <select name="subject_id" class="form-select" required onchange="loadStudents(this.value)">
                    <option value="">-- Select Subject --</option>
                    <?php while ($row = $subject_result->fetch_assoc()): ?>
                    <option value="<?= $row['subject_id'] ?>"><?= htmlspecialchars($row['subject_name']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="mb-3">
                <label>Select Student:</label>
                <select name="student_id" id="studentSelect" class="form-select" required>
                    <option value="">-- Select Student --</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Assign</button>
        </form>
    </div>

    <script>
    function loadStudents(subjectId) {
        if (subjectId !== "") {
            const xhr = new XMLHttpRequest();
            xhr.open("GET", "get_students_by_subject.php?subject_id=" + subjectId, true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    document.getElementById("studentSelect").innerHTML = xhr.responseText;
                }
            };
            xhr.send();
        }
    }
    </script>
</body>

</html>
<?php
include("../config/db.php");


if (isset($_POST['add_subject'])) {
    $subject_name = trim($_POST['subject_name']);
    if (!empty($subject_name)) {
        $stmt = $conn->prepare("INSERT INTO subjects (subject_name) VALUES (?)");
        $stmt->bind_param("s", $subject_name);
        $stmt->execute();
        $stmt->close();
        header("Location: manage_subjects.php");
        exit;
    }
}


if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM subjects WHERE subject_id = $id");
    header("Location: manage_subjects.php");
    exit;
}

if (isset($_POST['update_subject'])) {
    $subject_id = intval($_POST['subject_id']);
    $subject_name = trim($_POST['subject_name']);
    $stmt = $conn->prepare("UPDATE subjects SET subject_name = ? WHERE subject_id = ?");
    $stmt->bind_param("si", $subject_name, $subject_id);
    $stmt->execute();
    $stmt->close();
    header("Location: manage_subjects.php");
    exit;
}


$subjects = $conn->query("SELECT * FROM subjects");
?>

<!DOCTYPE html>
<html>

<head>
    <title>Manage Subjects</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container mt-5">
        <h2 class="text-center mb-4">📚 Manage Subjects</h2>


        <form method="post" class="row g-3 mb-4">
            <div class="col-md-8">
                <input type="text" name="subject_name" class="form-control" placeholder="Enter Subject Name" required>
            </div>
            <div class="col-md-4">
                <button type="submit" name="add_subject" class="btn btn-success w-100">Add Subject</button>
            </div>
        </form>


        <table class="table table-bordered table-striped">
            <thead class="table-primary">
                <tr>
                    <th>ID</th>
                    <th>Subject Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $subjects->fetch_assoc()) : ?>
                <tr>
                    <td><?= $row['subject_id'] ?></td>
                    <td>
                        <?php if (isset($_GET['edit']) && $_GET['edit'] == $row['subject_id']) : ?>
                        <form method="post" class="d-flex">
                            <input type="hidden" name="subject_id" value="<?= $row['subject_id'] ?>">
                            <input type="text" name="subject_name" value="<?= $row['subject_name'] ?>"
                                class="form-control me-2" required>
                            <button type="submit" name="update_subject" class="btn btn-primary btn-sm">Update</button>
                        </form>
                        <?php else : ?>
                        <?= htmlspecialchars($row['subject_name']) ?>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="?edit=<?= $row['subject_id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                        <a href="?delete=<?= $row['subject_id'] ?>" class="btn btn-danger btn-sm"
                            onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <a href="dashboard.php" class="btn btn-secondary mt-3">⬅ Back to Dashboard</a>
    </div>

</body>

</html>
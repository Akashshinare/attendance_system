<?php
include("../config/db.php"); 

$message = "";
$id = $name = $email = $mobile = $gender = $dob = $address = "";


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['student_id'] ?? '';
    $name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $mobile = trim($_POST['mobile']);
    $gender = $_POST['gender'];
    $dob = $_POST['dob'];
    $address = trim($_POST['address']);

    if ($id) {
       
        $stmt = $conn->prepare("UPDATE students SET full_name=?, email=?, mobile=?, gender=?, dob=?, address=? WHERE student_id=?");
        $stmt->bind_param("ssssssi", $name, $email, $mobile, $gender, $dob, $address, $id);
        $stmt->execute();
        $message = "✅ Student updated successfully!";
    } else {
   
        $stmt = $conn->prepare("INSERT INTO students (full_name, email, mobile, gender, dob, address) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $name, $email, $mobile, $gender, $dob, $address);
        $stmt->execute();
        $message = "✅ Student added successfully!";
    }
}


if (isset($_GET['delete'])) {
    $del_id = $_GET['delete'];
    $conn->query("DELETE FROM students WHERE student_id=$del_id");
    $message = "🗑️ Student deleted successfully!";
}


if (isset($_GET['edit'])) {
    $edit_id = $_GET['edit'];
    $result = $conn->query("SELECT * FROM students WHERE student_id=$edit_id");
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $id = $row['student_id'];
        $name = $row['full_name'];
        $email = $row['email'];
        $mobile = $row['mobile'];
        $gender = $row['gender'];
        $dob = $row['dob'];
        $address = $row['address'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Manage Students</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container mt-4">
        <h3 class="text-center mb-4">👨‍🎓 Manage Students</h3>

        <?php if ($message): ?>
        <div class="alert alert-info"><?= $message ?></div>
        <?php endif; ?>


        <form method="POST" class="card p-3 mb-4">
            <input type="hidden" name="student_id" value="<?= $id ?>">
            <div class="row mb-2">
                <div class="col">
                    <input type="text" name="full_name" class="form-control" placeholder="Full Name"
                        value="<?= $name ?>" required>
                </div>
                <div class="col">
                    <input type="email" name="email" class="form-control" placeholder="Email" value="<?= $email ?>">
                </div>
            </div>
            <div class="row mb-2">
                <div class="col">
                    <input type="text" name="mobile" class="form-control" placeholder="Mobile" value="<?= $mobile ?>">
                </div>
                <div class="col">
                    <select name="gender" class="form-control" required>
                        <option value="">Select Gender</option>
                        <option value="male" <?= $gender == "male" ? "selected" : "" ?>>Male</option>
                        <option value="female" <?= $gender == "female" ? "selected" : "" ?>>Female</option>
                        <option value="other" <?= $gender == "other" ? "selected" : "" ?>>Other</option>
                    </select>
                </div>
            </div>
            <div class="mb-2">
                <input type="date" name="dob" class="form-control" value="<?= $dob ?>">
            </div>
            <div class="mb-2">
                <textarea name="address" class="form-control" placeholder="Address"><?= $address ?></textarea>
            </div>
            <div>
                <button type="submit" class="btn btn-success"><?= $id ? "Update" : "Add" ?> Student</button>
                <a href="students.php" class="btn btn-secondary">Clear</a>
            </div>
        </form>


        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Mobile</th>
                    <th>Gender</th>
                    <th>DOB</th>
                    <th>Address</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
            $students = $conn->query("SELECT * FROM students ORDER BY student_id DESC");
            $i = 1;
            while ($row = $students->fetch_assoc()):
            ?>
                <tr>
                    <td><?= $i++ ?></td>
                    <td><?= htmlspecialchars($row['full_name']) ?></td>
                    <td><?= $row['email'] ?></td>
                    <td><?= $row['mobile'] ?></td>
                    <td><?= ucfirst($row['gender']) ?></td>
                    <td><?= $row['dob'] ?></td>
                    <td><?= $row['address'] ?></td>
                    <td>
                        <a href="?edit=<?= $row['student_id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                        <a href="?delete=<?= $row['student_id'] ?>" class="btn btn-sm btn-danger"
                            onclick="return confirm('Delete this student?')">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <a href="dashboard.php" class="btn btn-secondary mt-3">⬅ Back to Dashboard</a>
    </div>

</body>

</html>
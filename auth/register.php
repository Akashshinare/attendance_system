<?php
include("../config/db.php");
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = trim($_POST['full_name']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $mobile_number = trim($_POST['mobile_number']);
    $gender = $_POST['gender'];
    $dob = $_POST['dob'];
    $address = trim($_POST['address']);
    $user_role = $_POST['user_role'];

    if ($password !== $confirm_password) {
        $message = "<p style='color:red;'>❌ Passwords do not match.</p>";
    } else {
        $check = $conn->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
        $check->bind_param("ss", $username, $email);
        $check->execute();
        $result = $check->get_result();

        if ($result->num_rows > 0) {
            $message = "<p style='color:red;'>❌ Username or Email already exists.</p>";
        } else {
            $stmt = $conn->prepare("INSERT INTO users (username, email, password, full_name, mobile_number, gender, dob, address, user_role) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssssss", $username, $email, $password, $full_name, $mobile_number, $gender, $dob, $address, $user_role);

            if ($stmt->execute()) {
                $message = "<p style='color:green;'>✅ Registration successful. <a href='login.php'>Click here to Login</a></p>";
            } else {
                $message = "<p style='color:red;'>❌ Error: Could not register user.</p>";
            }

            $stmt->close();
        }
        $check->close();
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>User Registration - Student Attendance System</title>
    <meta charset="UTF-8">
    <style>
    body {
        font-family: Arial;
        margin: 50px;
        background-color: #f9f9f9;
    }

    form {
        width: 400px;
        margin: auto;
        padding: 25px;
        border: 1px solid #ccc;
        background-color: white;
        border-radius: 8px;
    }

    input,
    select,
    textarea {
        width: 100%;
        padding: 8px;
        margin: 8px 0 16px 0;
        box-sizing: border-box;
    }

    button,
    .btn-link {
        padding: 10px 20px;
        border: none;
        text-decoration: none;
        display: inline-block;
        text-align: center;
        border-radius: 5px;
        font-size: 15px;
        cursor: pointer;
    }

    button {
        background-color: #28a745;
        color: white;
        margin-right: 10px;
    }

    .btn-link {
        background-color: #007bff;
        color: white;
    }

    .btn-link:hover {
        background-color: #0056b3;
    }

    h2 {
        text-align: center;
        margin-bottom: 20px;
    }

    .message {
        text-align: center;
        font-weight: bold;
        margin-bottom: 15px;
    }

    .form-buttons {
        text-align: center;
    }
    </style>
</head>

<body>

    <h2>User Registration</h2>
    <div class="message"><?= $message ?? '' ?></div>

    <form method="post" action="">
        <label>Full Name:</label>
        <input type="text" name="full_name" required>

        <label>Username:</label>
        <input type="text" name="username" required>

        <label>Email:</label>
        <input type="email" name="email" required>

        <label>Password:</label>
        <input type="password" name="password" required>

        <label>Confirm Password:</label>
        <input type="password" name="confirm_password" required>

        <label>Mobile Number:</label>
        <input type="text" name="mobile_number" maxlength="15">

        <label>Gender:</label>
        <select name="gender" required>
            <option value="">--Select--</option>
            <option value="male">Male</option>
            <option value="female">Female</option>
            <option value="other">Other</option>
        </select>

        <label>Date of Birth:</label>
        <input type="date" name="dob">

        <label>Address:</label>
        <textarea name="address" rows="3"></textarea>

        <label>User Role:</label>
        <select name="user_role" required>
            <option value="">--Select Role--</option>
            <option value="admin">Admin</option>
            <option value="teacher">Teacher</option>
            <option value="student">Student</option>
        </select>

        <div class="form-buttons">
            <button type="submit">Register</button>
            <a href="login.php" class="btn-link">Login</a>
        </div>
    </form>

</body>

</html>
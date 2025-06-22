<?php
session_start();
include("../config/db.php");

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        
        if ($password === $user['password']) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_role'] = $user['user_role'];

           
            if ($user['user_role'] === 'admin') {
                header("Location: ../admin/dashboard.php");
            } elseif ($user['user_role'] === 'teacher') {
                header("Location: ../teacher/dashboard.php");
            } elseif ($user['user_role'] === 'student') {
                header("Location: ../student/dashboard.php");
            }
            exit;
        } else {
            $message = "<p style='color:red;'>❌ Invalid password.</p>";
        }
    } else {
        $message = "<p style='color:red;'>❌ Username not found.</p>";
    }

    $stmt->close();
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Login - Student Attendance System</title>
    <meta charset="UTF-8">
    <style>
    body {
        font-family: Arial, sans-serif;
        margin: 50px;
        background-color: #f0f0f0;
    }

    form {
        width: 400px;
        margin: auto;
        padding: 25px;
        background: white;
        border-radius: 8px;
        border: 1px solid #ccc;
    }

    input {
        width: 100%;
        padding: 10px;
        margin: 10px 0;
        box-sizing: border-box;
    }

    button {
        width: 100%;
        padding: 10px;
        background-color: #007bff;
        color: white;
        border: none;
        font-size: 16px;
        cursor: pointer;
        margin-top: 10px;
    }

    button:hover {
        background-color: #0056b3;
    }

    h2 {
        text-align: center;
    }

    .message {
        text-align: center;
        font-weight: bold;
        margin-bottom: 10px;
    }

    .form-footer {
        text-align: center;
        margin-top: 15px;
    }

    .form-footer a {
        text-decoration: none;
        color: #007bff;
    }

    .form-footer a:hover {
        text-decoration: underline;
    }
    </style>
</head>

<body>

    <h2>Login</h2>
    <div class="message"><?= $message ?? '' ?></div>

    <form method="post" action="">
        <label>Username:</label>
        <input type="text" name="username" required>

        <label>Password:</label>
        <input type="password" name="password" required>

        <button type="submit">Login</button>

        <div class="form-footer">
            <p>Don't have an account? <a href="register.php">Register here</a></p>
        </div>
    </form>

</body>

</html>
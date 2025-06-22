<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['user_role'] !== 'student') {
    header("Location: ../auth/login.php");
    exit;
}

include("../config/db.php");

$username = $_SESSION['username'];


$stmt = $conn->prepare("SELECT user_id, full_name FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$user_id = $user['user_id'];
$full_name = $user['full_name'];

$month = date('m');
$year = date('Y');

$attendance_query = $conn->prepare("SELECT attendance_date, status FROM attendance WHERE student_id = ? AND MONTH(attendance_date) = ? AND YEAR(attendance_date) = ?");
$attendance_query->bind_param("iii", $user_id, $month, $year);
$attendance_query->execute();
$result = $attendance_query->get_result();

$attendance_data = [];
while ($row = $result->fetch_assoc()) {
    $attendance_data[$row['attendance_date']] = $row['status'];
}

function draw_calendar($month, $year, $attendance_data) {
    $calendar = '<table class="table table-bordered">';
    $headings = ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'];
    $calendar .= '<tr>';
    foreach($headings as $heading) {
        $calendar .= '<th class="text-center">'.$heading.'</th>';
    }
    $calendar .= '</tr><tr>';

    $running_day = date('w', mktime(0,0,0,$month,1,$year));
    $days_in_month = date('t', mktime(0,0,0,$month,1,$year));
    $day_counter = 0;

    for ($x = 0; $x < $running_day; $x++) {
        $calendar .= '<td></td>';
        $day_counter++;
    }

    for($day = 1; $day <= $days_in_month; $day++) {
        $date = "$year-$month-".str_pad($day, 2, "0", STR_PAD_LEFT);
        $status = $attendance_data[$date] ?? '';

        if ($status === 'present') {
            $calendar .= '<td class="bg-success text-white text-center">'.$day.'<br><small>✔ Present</small></td>';
        } elseif ($status === 'absent') {
            $calendar .= '<td class="bg-danger text-white text-center">'.$day.'<br><small>✖ Absent</small></td>';
        } else {
            $calendar .= '<td class="text-center">'.$day.'</td>';
        }

        $day_counter++;
        if ($day_counter % 7 == 0) {
            $calendar .= '</tr><tr>';
        }
    }

    while ($day_counter % 7 != 0) {
        $calendar .= '<td></td>';
        $day_counter++;
    }

    $calendar .= '</tr></table>';
    return $calendar;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Monthly Attendance Calendar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4">🗓️ Attendance Calendar - <?= date('F Y') ?></h2>
        <div class="text-end mb-3">
            <strong>Student:</strong> <?= htmlspecialchars($full_name) ?>
            <a href="dashboard.php" class="btn btn-primary btn-sm ms-3">Back to Dashboard</a>
            <a href="../auth/logout.php" class="btn btn-danger btn-sm ms-2">Logout</a>
        </div>

        <?= draw_calendar($month, $year, $attendance_data) ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
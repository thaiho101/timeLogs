<?php
date_default_timezone_set('America/Chicago'); // CDT
require_once("./config.php");

$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($conn->connect_error) {
    die(json_encode(['status' => 'error', 'message' => 'DB connection failed']));
}

$pin = $_POST['code'] ?? '';

if (!$pin || strlen($pin) !== 4) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid PIN']);
    exit;
}

$stmt = $conn->prepare("SELECT *
                        FROM users
                        WHERE code = ?");
$stmt->bind_param("s" , $pin);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    echo json_encode(['status' => 'not_found']);
    exit;
}

$user_id = $user['id'];
$today = date('Y-m-d');
$now = date('Y-m-d H:i:s');
list($date, $time) = explode(' ', $now);

$stmt = $conn->prepare("SELECT * FROM attendance a
                WHERE user_id = ?
                AND logout_time IS NULL
                ORDER BY login_time DESC 
                LIMIT 1");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$attendance = $result->fetch_assoc();

if ($attendance) {
    //log out
    $stmt = $conn->prepare("UPDATE attendance
                            SET logout_time = ? 
                            WHERE id = ?");
    $stmt->bind_param('si', $now, $attendance['id']);
    $stmt->execute();

    $login_time = $attendance['login_time'];

    $login_timestamp = strtotime($login_time);
    $logout_timestamp = strtotime($now);

    $diff_seconds = $logout_timestamp - $login_timestamp;

    $hours = floor($diff_seconds / 3600);
    $minutes = floor(($diff_seconds % 3600) / 60);

    $total_time = "{$hours} hours {$minutes} minutes.";

    // echo json_encode(['status' => 'logout', 'name' => $user['first_name'], 'time' => $now, 'totalTime' => $total_time]);
        echo json_encode(['status' => 'logout', 'name' => $user['first_name'], 'date' => $date, 'time' => $time, 'totalTime' => $total_time]);
} else {
    //log in
    $stmt = $conn->prepare("INSERT INTO attendance
                            (user_id, login_time) VALUES (?, ?)");
    $stmt->bind_param('is', $user_id, $now);
    $stmt->execute();
    // echo json_encode(['status' => 'login', 'name' => $user['first_name'], 'time' => $now]);
    echo json_encode(['status' => 'login', 'name' => $user['first_name'], 'date' => $date, 'time' => $time]);
}

$conn->close();

?>
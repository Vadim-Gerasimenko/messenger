<?php
session_start();
require_once "../actions/db_connection.php";

$user_id = isset($_GET['user_id']) ? (int) $_GET['user_id'] : -1;

$sql = "
SELECT last_seen_date FROM users
WHERE id = ?
";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(["success" => false, "message" => "Unknown user id!"]);
}

$row = $result->fetch_assoc();

echo json_encode(["success" => true, "last_seen_date" => $row["last_seen_date"]]);

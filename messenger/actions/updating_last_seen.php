<?php
session_start();

require_once "../functions/helpers.php";
require_once "../constants/configure_constants.php";

if (!is_auth()) {
    echo json_encode(["success" => false, "message" => "Access denied!"]);
    die();
}

require_once "../actions/db_connection.php";

$user_id = isset($_SESSION["user_id"]) ? (int)$_SESSION["user_id"] : -1;


$datetime = new DateTime("now", new DateTimeZone(TIME_ZONE));
$datetime->modify("+5 minutes");
$date = $datetime->format(DATE_TIME_FORMAT);

$sql = "UPDATE users SET last_seen_date = ? WHERE id = ?";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param("si", $date, $user_id);
$stmt->execute();

echo json_encode(["success" => true, "message" => "Successfully updated last seen!"]);
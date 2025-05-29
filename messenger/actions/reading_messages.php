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
$dialog_id = isset($_POST["dialog_id"]) ? (int)$_POST["dialog_id"] : -1;

$sql = "
SELECT id, first_user_id, second_user_id FROM dialogs
WHERE id = ? AND (first_user_id = ? OR second_user_id = ?)
";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param("iii", $dialog_id, $user_id, $user_id);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(["success" => false, "message" => "Dialog not found!"]);
    die();
}

date_default_timezone_set(TIME_ZONE);
$date = date(DATE_TIME_FORMAT);

$sql = "UPDATE messages SET read_date = ? WHERE sender_id = ? AND receiver_id = ? AND read_date IS NULL";

foreach ($result as $row) {
    $sender_id = $row["first_user_id"] == $user_id ? $row["second_user_id"] : $row["first_user_id"];

    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("sii", $date, $sender_id, $user_id);
    $stmt->execute();
}

echo json_encode(["success" => true, "message" => null]);
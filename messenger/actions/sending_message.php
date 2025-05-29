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
$receiver_id = isset($_POST["receiver_id"]) ? (int)$_POST["receiver_id"] : -1;

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

$type_id = isset($_POST["type_id"]) ? (int)$_POST["type_id"] : -1;

$types_ids = [1, 2, 3, 4];
$types_with_files = [2, 3, 4];

if (!in_array($type_id, $types_ids)) {
    echo json_encode(["success" => false, "message" => "Incorrect message type!"]);
    die();
}

$text = $_POST["text"] ?? "";
$text = trim(filter_var($text, FILTER_SANITIZE_SPECIAL_CHARS));

if (!in_array($type_id, $types_ids) && empty($text)) {
    echo json_encode(["success" => false, "message" => "Empty message text!"]);
    die();
}

if (in_array($type_id, $types_with_files) && empty($_FILES)) {
    echo json_encode(["success" => false, "message" => "No files uploaded!"]);
    die();
}

date_default_timezone_set(TIME_ZONE);
$date = date(DATE_TIME_FORMAT);

$sql = "INSERT INTO messages (dialog_id, sender_id, receiver_id, type_id, text, sent_date) VALUES (?, ?, ?, ?, ?, ?)";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param("iiiiss", $dialog_id, $user_id, $receiver_id, $type_id, $text, $date);
$stmt->execute();

if (!in_array($type_id, $types_with_files)) {
    echo json_encode(["success" => true, "message" => "Message sent!"]);
    die();
}

$keysString = $_POST["file_keys"];

if (empty($keysString)) {
    echo json_encode(["success" => false, "message" => "Files keys not found!"]);
}

$message_id = $stmt->insert_id;
$uploaded_files = [];

$rel_dir = match ($type_id) {
    2 => "/assets/data/messages/uploads/images/",
    3 => "/assets/data/messages/uploads/videos/",
    default => "/assets/data/messages/uploads/files/"
};

$keys = explode(",",str_replace(".", "_", $keysString));

require_once "file_upload.php";

$sql_file = "INSERT INTO files (name, size, path, type) VALUES (?, ?, ?, ?)";
$sql_message_file = "INSERT INTO messages_files(message_id, file_id) VALUES (?, ?)";

foreach ($keys as $key) {
    if ($_FILES[$key]["error"] !== UPLOAD_ERR_OK) {
        continue;
    }

    $file_data = [
        "name" => $_FILES[$key]["name"],
        "tmp_name" => $_FILES[$key]["tmp_name"],
        "size" => $_FILES[$key]["size"],
        "type" => $_FILES[$key]["type"]
    ];

    $file_path = upload_file($file_data, $rel_dir, "msg");

    $stmt_file = $mysqli->prepare($sql_file);
    $stmt_file->bind_param("siss", $file_data["name"], $file_data["size"], $file_path, $file_data["type"]);
    $stmt_file->execute();

    $file_id = $stmt_file->insert_id;

    $stmt_message_file = $mysqli->prepare($sql_message_file);
    $stmt_message_file->bind_param("ii", $message_id, $file_id);
    $stmt_message_file->execute();
}

echo json_encode(["success" => true, "message" => "Files uploaded!"]);
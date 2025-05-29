<?php
session_start();

require_once "../functions/helpers.php";

if (!is_auth()) {
    echo json_encode(["success" => false, "message" => "Access denied!"]);
    die();
}

require_once "../actions/db_connection.php";

$dialog_id = isset($_GET["dialog_id"]) ? (int)$_GET["dialog_id"] : -1;
$last_message_id = isset($_GET["message_id"]) ? (int)$_GET["message_id"] : -1;
$current_id = $_SESSION["user_id"];

$sql = "
SELECT us.id user_id, us.name, us.surname, f.path avatar_path FROM dialogs d
JOIN users us ON us.id = d.second_user_id OR us.id = d.first_user_id
LEFT JOIN files f ON us.avatar_id = f.id
WHERE d.id = ? AND (d.first_user_id = ? OR d.second_user_id = ?) AND us.id <> ?
";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param("iiii", $dialog_id, $current_id, $current_id, $current_id);
$stmt->execute();

$result = $stmt->get_result();
$dialog_info = $result->fetch_assoc();

if (!isset($dialog_info)) {
    echo json_encode(["success" => false, "message" => "Access denied!"]);
    die();
}

$sql = "
SELECT me.id, me.sender_id, me.receiver_id, me.text, me.sent_date, mt.type_name,
       f.id file_id, f.path file_path, f.name file_name, f.size file_size, f.type file_type 
FROM messages me
JOIN messages_types mt ON mt.id = me.type_id
LEFT JOIN messages_files mf ON me.id = mf.message_id
LEFT JOIN files f ON f.id = mf.file_id
WHERE dialog_id = ? AND me.id > ?
ORDER BY me.sent_date
";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param("ii", $dialog_id, $last_message_id);
$stmt->execute();
$result = $stmt->get_result();

$messages = [];
$processed_messages = [];

foreach ($result as $row) {
    $message_id = $row["id"];

    if (!isset($processed_messages[$message_id])) {
        $processed_messages[$message_id] = [
            "id" => $row["id"],
            "sender_id" => $row["sender_id"],
            "receiver_id" => $row["receiver_id"],
            "type_name" => $row["type_name"],
            "text" => $row["text"],
            "sent_date" => $row["sent_date"],
            "files" => []
        ];

        if ($row["file_id"]) {
            $fileData = [
                "id" => $row["file_id"],
                "path" => $row["file_path"],
                "name" => $row["file_name"],
                "size" => $row["file_size"],
                "type" => $row["file_type"]
            ];

            $processed_messages[$message_id]['files'][] = $fileData;
        }
    } else if ($row["file_id"]) {
        $processed_messages[$message_id]["files"][] = [
            "id" => $row["file_id"],
            "path" => $row["file_path"],
            "name" => $row["file_name"],
            "size" => $row["file_size"],
            "type" => $row["file_type"]
        ];
    }
}

$final_messages = array_values($processed_messages);

echo json_encode(["success" => true, "dialog_info" => $dialog_info, "messages" => $final_messages]);

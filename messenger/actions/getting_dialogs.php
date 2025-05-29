<?php
session_start();

require_once "../functions/helpers.php";
require_once "../constants/configure_constants.php";

if (!is_auth()) {
    echo json_encode(["success" => false, "message" => "Access denied!"]);
    die();
}

require_once "../actions/db_connection.php";

$sql = "
SELECT d.id dialog_id, us.id user_id, us.name, us.surname,
       f.path avatar_path,
       MAX(me.sent_date) last_message_date,
       (
           SELECT COUNT(id) FROM messages
           WHERE sender_id = us.id AND receiver_id = ? AND read_date IS NULL
       ) unread_messages_number,
       (
           SELECT text FROM messages
           WHERE sent_date = MAX(me.sent_date)
       ) last_message_text,
       (
           SELECT sender_id FROM messages
           WHERE sent_date = MAX(me.sent_date)
       ) last_message_sender_id
FROM users us
JOIN messages me ON me.sender_id = us.id  OR me.receiver_id = us.id
LEFT JOIN dialogs d ON us.id = d.first_user_id OR us.id = d.second_user_id
LEFT JOIN files f ON f.id = us.avatar_id
WHERE us.id <> ? AND (d.first_user_id = ? OR d.second_user_id = ?) AND (us.name LIKE ? OR us.surname LIKE ? OR us.email LIKE ?)
GROUP BY d.id, us.id
ORDER BY last_message_date DESC
LIMIT ? OFFSET ?;
";

$stmt = $mysqli->prepare($sql);

$limit = 25;
$start = 0;

$current_id = $_SESSION["user_id"];
$pattern = "%" . (empty($_GET["pattern"]) ? "" : trim(strtolower($_GET["pattern"]))) . "%";

$stmt->bind_param("iiiisssii", $current_id, $current_id, $current_id,$current_id, $pattern, $pattern, $pattern, $limit, $start);
$stmt->execute();

$result = $stmt->get_result();
$rows = [];

foreach ($result as $row) {
    $rows[] = $row;
}

echo json_encode(["success" => true, "data" => $rows]);
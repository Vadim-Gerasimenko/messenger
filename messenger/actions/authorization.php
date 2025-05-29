<?php
session_start();

require_once "../functions/validation.php";
require_once "../functions/helpers.php";
require_once "../constants/paths.php";

$email = trim(filter_var($_POST["email"], FILTER_SANITIZE_EMAIL));
$password = sanitize_text($_POST["password"]);

set_old_value("auth_email", $email);

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    add_validation_error("auth_email", "Недопустимый email");
}

if (empty($password)) {
    add_validation_error("auth_password", "Введите пароль");
}

if (!empty($_SESSION["validation"])) {
    redirect(AUTHORIZATION_PATH);
}

require_once "db_connection.php";

$sql = "
SELECT us.id, us.name name, us.surname, us.password, f.path 
FROM users us 
JOIN files f ON us.avatar_id = f.id
WHERE email = ?
";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();

$result = $stmt->get_result();

function add_login_or_password_error(): void
{
    add_validation_error("auth_data", "Неверный email и/или пароль");
    redirect(AUTHORIZATION_PATH);
}

if ($result->num_rows == 0) {
    add_login_or_password_error();
}

$row = $result->fetch_assoc();

if (!password_verify($password, $row["password"])) {
    add_login_or_password_error();
}

$user_id = $row["id"];
$user_name = $row["name"];
$user_surname = $row["surname"];
$avatar_path = $row["path"];

require_once "start_auth_session.php";
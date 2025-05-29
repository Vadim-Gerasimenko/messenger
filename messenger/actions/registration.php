<?php
session_start();

$name = trim(filter_var($_POST["name"], FILTER_SANITIZE_SPECIAL_CHARS));
$surname = trim(filter_var($_POST["surname"], FILTER_SANITIZE_SPECIAL_CHARS));
$email = trim(filter_var($_POST["email"], FILTER_SANITIZE_EMAIL));
$password = filter_var($_POST["password"], FILTER_SANITIZE_SPECIAL_CHARS);
$confirmed_password = filter_var($_POST["confirmed_password"], FILTER_SANITIZE_SPECIAL_CHARS);

$avatar = $_FILES["avatar"] ?? [];

$avatar_path = null;
$avatar_id = null;

require_once "../functions/validation.php";
require_once "../functions/helpers.php";
require_once "../constants/configure_constants.php";
require_once "../constants/paths.php";

if (empty($name)) {
    add_validation_error("reg_name", "Введите имя");
}

if (empty($surname)) {
    add_validation_error("reg_surname", "Введите фамилию");
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    add_validation_error("reg_email", "Недопустимый email");
}

if (strlen($password) < PASSWORD_MIN_SIZE) {
    add_validation_error("reg_password", "Пароль не может содержать менее " . PASSWORD_MIN_SIZE . " символов");
} elseif ($confirmed_password != $password) {
    add_validation_error("reg_password", "Пароли не совпадают");
    add_validation_error("reg_confirmed_password", "Пароли не совпадают");
}

$is_valid_upload_file = is_valid_upload_file($avatar, "reg_avatar");

if (!empty($_SESSION["validation"])) {
    set_old_value("reg_name", $name);
    set_old_value("reg_surname", $surname);
    set_old_value("reg_email", $email);
    redirect(REGISTRATION_PATH);
}

require_once "db_connection.php";

$sql = "SELECT id FROM users WHERE email = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows > 0) {
    set_old_value("reg_email", $email);
    add_validation_error("exist_reg_email", "Пользователь с указанной почтой уже существует");
    redirect(REGISTRATION_PATH);
}

if ($is_valid_upload_file) {
    require_once "file_upload.php";

    $avatar_path = upload_file($avatar, SERVICE_AVATARS_DIR, AVATAR_PREFIX);

    $query = "INSERT INTO files(path) VALUES(?)";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("s",$avatar_path);
    $stmt->execute();
}

if (!empty($avatar_path)) {
    $avatar_id = $stmt->insert_id;
}

$password = password_hash($password, PASSWORD_DEFAULT);

$query = "INSERT INTO users(name, surname, email, password, avatar_id) VALUES (?,?,?,?,?);";

$stmt = $mysqli->prepare($query);
$stmt->bind_param("ssssi", $name, $surname, $email, $password, $avatar_id);
$stmt->execute();

$user_id = $stmt->insert_id;
$user_name = $name;
$user_surname = $surname;
require_once "start_auth_session.php";

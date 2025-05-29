<?php
function is_valid_news_fields(string $method_name): bool
{
    $title = trim_sanitize_text($_POST["title"]);
    $stext = trim_sanitize_text($_POST["stext"]);
    $ftext = trim_sanitize_text($_POST["ftext"]);
    $src = trim_sanitize_text($_POST["src"]);

    $title_field = $method_name . "_title";
    $stext_field = $method_name . "_stext";
    $ftext_field = $method_name . "_ftext";
    $img_field = $method_name . "_img";
    $src_field = $method_name . "_src";

    if (empty($title)) {
        add_validation_error($title_field, "Введите заголовок");
    }

    if (empty($stext)) {
        add_validation_error($stext_field, "Введите превью");
    }

    if (empty($ftext)) {
        add_validation_error($ftext_field, "Введите основной текст");
    }

    $img = $_FILES["img"] ?? [];
    is_valid_upload_file($img, $img_field);

    if (!empty($_SESSION["validation"])) {
        set_old_value($title_field, $title);
        set_old_value($stext_field, $stext);
        set_old_value($ftext_field, $ftext);
        set_old_value($src_field, $src);

        return false;
    }

    set_temp_value($title_field, $title);
    set_temp_value($stext_field, $stext);
    set_temp_value($ftext_field, $ftext);
    set_temp_value($src_field, $src);

    return true;
}

function is_valid_upload_file($file, $field_name): bool
{
    require_once "../constants/configure_constants.php";

    if (empty($file["full_path"])) {
        return false;
    }

    if (!in_array($file["type"], FILE_TYPES)) {
        add_validation_error($field_name, "Неверный тип изображения");
        return false;
    }

    if (($file["size"] / BYTE_IN_MEGABYTE) >= UPLOAD_FILE_MAX_SIZE_MB) {
        add_validation_error($field_name, "Размер изображения не может превышать " . UPLOAD_FILE_MAX_SIZE_MB . " Мб");
        return false;
    }

    return true;
}

function add_validation_error($field_name, $message): void
{
    $_SESSION["validation"]["$field_name"] = $message;
}

function is_invalid(string $key): bool
{
    return isset($_SESSION["validation"]["$key"]);
}

function get_error_message(string $key): string
{
    $error_message = $_SESSION["validation"]["$key"] ?? "";
    unset($_SESSION["validation"]["$key"]);

    return $error_message;
}

function set_old_value(string $key, mixed $value): void
{
    $_SESSION["old"][$key] = $value;
}

function get_old_value(string $key)
{
    $old_value = $_SESSION["old"]["$key"] ?? "";
    unset($_SESSION["old"]["$key"]);

    return $old_value;
}

function set_temp_value(string $key, mixed $value): void
{
    $_SESSION["temp"][$key] = $value;
}

function get_temp_value(string $key)
{
    $old_value = $_SESSION["temp"]["$key"] ?? "";
    unset($_SESSION["temp"]["$key"]);

    return $old_value;
}
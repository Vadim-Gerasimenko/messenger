<?php
require_once "../constants/paths.php";

function upload_file(array  $file,
                     string $rel_dir,
                     string $file_prefix = "file"): string
{
    $upload_dir = SERVER_ROOT_DIR . $rel_dir;

    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, DIR_FOR_UPLOAD_FILES_PERMISSION, true);
    }

    $extension = pathinfo($file["name"], PATHINFO_EXTENSION);

    if (empty($extension)) {
        $extension = get_extension_by_mime($file["type"]);
    }

    $file_name = $file_prefix . "_" . time() . ".$extension";
    $path = $upload_dir . $file_name;

    if (!move_uploaded_file($file["tmp_name"], "$path")) {
        die("Sorry, there was an error uploading your file.");
    }

    return substr($path, strrpos($path, ASSETS_DATA_DIR) + strlen(ASSETS_DATA_DIR));
}

function get_extension_by_mime(string $mime): string
{
    $mime_map = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/gif' => 'gif',
        'video/mp4' => 'mp4',
        'video/quicktime' => 'mov',
        'application/pdf' => 'pdf',
        'application/msword' => 'doc',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'docx',
        'application/vnd.ms-excel' => 'xls',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'xlsx',
        'application/zip' => 'zip',
        'application/x-rar-compressed' => 'rar'
    ];

    return $mime_map[$mime] ?? 'bin';
}
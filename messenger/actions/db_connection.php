<?php
$mysqli = new mysqli("localhost", "root1", "root", "messenger");

if ($mysqli->connect_errno) {
    $_SESSION["error500"] = "Не удалось подключиться к MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
    redirect(ERROR_500_PATH);
}

$mysqli->query("set names utf8");
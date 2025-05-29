<?php
session_start();
$_SESSION["is_auth"] = true;
$_SESSION["user_id"] = $user_id;
$_SESSION["user_name"] = $user_name;
$_SESSION["user_surname"] = $user_surname;
$_SESSION["avatar_path"] = $avatar_path;
redirect(HOMEPAGE_PATH);
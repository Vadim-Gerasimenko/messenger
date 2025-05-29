<?php
session_start();
session_destroy();

require_once "../functions/helpers.php";
require_once "../constants/paths.php";

redirect(HOMEPAGE_PATH);
<?php
session_start();
require_once "constants/paths.php";

require_once "functions/helpers.php";
require_once "actions/db_connection.php";
require_once "functions/validation.php";

if (is_error_500()) {
    redirect(ERROR_500_PATH);
}

$url = $_SERVER['REQUEST_URI'];
$url = explode('?', $url);
$url = $url[0];

$is_homepage = $url == HOMEPAGE_PATH || $url == HOMEPAGE_DIR;

$navigation_tab_link = "";
$navigation_tab = "";
$avatar_path = null;

if (!is_auth()) {
    $navigation_tab = "Войти";
    $navigation_tab_link = AUTHORIZATION_PATH;
} else {
    if (isset($_SESSION["avatar_path"])) {
        $avatar_path = FULL_DOMAIN . ASSETS_DATA_DIR . $_SESSION["avatar_path"];
    }

    if (empty($avatar_path)) {
        $avatar_path = FULL_DOMAIN . ASSETS_DATA_DIR . DEFAULT_AVATAR_PATH;
    }

    $user_name = $_SESSION["user_name"] ?? "";
    $user_surname = $_SESSION["user_surname"] ?? "";

    $navigation_tab = "Выйти";
    $navigation_tab_link = LOGOUT_ACTION_PATH;
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $page_title ?></title>
    <link rel="icon" href="<?= DEFAULT_ICON_LOGO_PATH ?>" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
          integrity="sha384-tViUnnbYAV00FLIhhi3v/dWt3Jxw4gZQcNoSCxCIFNJVCx7/D55/wXsrNIRANwdD" crossorigin="anonymous">
    <link href="/assets/css/style.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq"
            crossorigin="anonymous">
    </script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
            integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous">
    </script>
</head>
<body>
<header class="mb-3">
    <nav class="navbar bg-body-tertiary sticky-top">
        <div class="container-fluid ms-4">
            <div>
                <?php if (is_auth()) { ?>
                    <img src="<?= $avatar_path ?>" class="rounded-circle me-2"
                         width="40px" height="40px" alt="avatar">
                    <span><?= $user_name ?></span>
                    <span><?= $user_surname ?></span>

                <?php } ?>
            </div>
            <div>
                <a href="<?= $navigation_tab_link ?>"
                   class="me-2 link-body-emphasis link-offset-2 link-underline-opacity-25 link-underline-opacity-75-hover">
                    <?= $navigation_tab ?>
                </a>
            </div>
        </div>
    </nav>
</header>

<?php if (is_auth()) { ?>
    <script src="/assets/js/online.js"></script>
<?php } ?>
<?php
require_once "constants/configure_constants.php";
$page_title = SITE_NAME . ". Ошибка";

require_once "blocks/header.php";
require('actions/db_connection.php');

if (!is_error_500()) {
    redirect(HOMEPAGE_PATH);
}
?>

<main>
    <div class="container vh-100">
        <div class="alert alert-danger d-flex align-items-center" role="alert">
            <div>
                <h3>Что-то пошло не так...</h3>
                <div class=""><?= $_SESSION["error_500"] ?? "" ?></div>
            </div>
        </div>
    </div>
</main>

<?php require_once "blocks/footer.php"; ?>
</body>
</html>
<?php
require_once "constants/configure_constants.php";
$page_title = SITE_NAME . ". Авторизация";

require_once "blocks/header.php";

if (is_auth()) {
    redirect(HOMEPAGE_PATH);
}
?>

<main class="vh-100">
    <div class="container">
        <div class="row justify-content-center">
            <div class="m-2 card col-11 col-xl-6">
                <div class="card-body">
                    <h3 class="card-title">Вход</h3>

                    <form method="post" action="<?= PROJECT_ADDRESS . AUTHORIZATION_ACTION_PATH ?>">
                        <div class="mt-3 mb-4 justify-content-center">
                            <div class="mb-3">
                                <label for="auth-email" class="form-label was-validated">Адрес электронной
                                    почты</label>
                                <input type="email" id="auth-email" name="email"
                                       value="<?= get_old_value("auth_email") ?>"
                                       class="form-control <?php if (is_invalid("auth_email")) { ?> is-invalid <?php } ?>">

                                <?php if (is_invalid("auth_email")) { ?>
                                    <div class="form-text mb-3 invalid-feedback d-block">
                                        <?= get_error_message("auth_email") ?>
                                    </div>
                                <?php } ?>
                            </div>

                            <div class="mb-3 row-cols-8">
                                <label for="auth-password" class="form-label">Пароль</label>
                                <input type="password" id="auth-password" name="password"
                                       class="form-control <?php if (is_invalid("auth_password")) { ?> is-invalid <?php } ?>">

                                <?php if (is_invalid("auth_password")) { ?>
                                    <div class="form-text mb-3 invalid-feedback d-block">
                                        <?= get_error_message("auth_password") ?>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>

                        <?php if (is_invalid("auth_data")) { ?>
                            <div class="d-flex justify-content-center invalid-feedback mb-3">
                                <?= get_error_message("auth_data") ?>
                            </div>
                        <?php } ?>

                        <div class="justify-content-center row">
                            <button type="submit" class="btn btn-outline-primary col-5">Войти</button>
                        </div>
                    </form>

                    <div class="mt-2 d-flex justify-content-center">
                        <a href="<?= PROJECT_ADDRESS . REGISTRATION_PATH ?>"
                           class="link-secondary link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover"
                        >Ещё не зарегистрированы?</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php
require_once "blocks/footer.php";
?>
</body>
</html>
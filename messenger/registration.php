<?php
require_once "constants/configure_constants.php";
$page_title = SITE_NAME . ". Регистрация";

require_once "blocks/header.php";
require_once "functions/validation.php";

if (is_auth()) {
    redirect(HOMEPAGE_PATH);
}
?>

<main class="vh-100">
    <div class="container">
        <div class="row justify-content-center">
            <div class="m-2 card col-11 col-xl-6">
                <div class="card-body">
                    <h3 class="card-title">Регистрация</h3>

                    <form method="post" action="<?= REGISTRATION_ACTION_PATH ?>" enctype="multipart/form-data">
                        <div class="mt-3 mb-3 justify-content-center row" aria-describedby="info-help">
                            <div class="mb-3 col-md-6">
                                <label for="reg-surname" class="form-label">
                                    <span class="text-primary h5">*</span>
                                    <span>Фамилия</span>
                                </label>
                                <input type="text" id="reg-surname" name="surname"
                                       value="<?= get_old_value("reg_surname") ?>"
                                       class="form-control <?php if (is_invalid("reg_surname")) { ?> is-invalid <?php } ?>">

                                <?php if (is_invalid("reg_surname")) { ?>
                                    <div class="form-text mb-3 invalid-feedback d-block">
                                        <?= get_error_message("reg_surname") ?>
                                    </div>
                                <?php } ?>
                            </div>

                            <div class="mb-3 col-md-6">
                                <label for="reg-name" class="form-label">
                                    <span class="text-primary h5">*</span>
                                    <span>Имя</span>
                                </label>
                                <input type="text" id="reg-name" name="name"
                                       value="<?= get_old_value("reg_name") ?>"
                                       class="form-control <?php if (is_invalid("reg_name")) { ?> is-invalid <?php } ?>">

                                <?php if (is_invalid("reg_name")) { ?>
                                    <div class="form-text mb-3 invalid-feedback d-block">
                                        <?= get_error_message("reg_name") ?>
                                    </div>
                                <?php } ?>
                            </div>

                            <div class="mb-3">
                                <label for="reg-email" class="form-label">
                                    <span class="text-primary h5">*</span>
                                    <span>Адрес электронной почты</span>
                                </label>
                                <input type="email" id="reg-email" name="email"
                                       value="<?= get_old_value("reg_email") ?>"
                                       class="form-control <?php if (is_invalid("reg_email")) { ?> is-invalid <?php } ?>">

                                <?php if (is_invalid("reg_email")) { ?>
                                    <div class="form-text mb-3 invalid-feedback d-block">
                                        <?= get_error_message("reg_email") ?>
                                    </div>
                                <?php } ?>
                            </div>

                            <div class="mb-3">
                                <label for="reg-password" class="form-label">
                                    <span class="text-primary h5">*</span>
                                    <span>Придумайте пароль</span>
                                </label>
                                <input type="password" id="reg-password" name="password"
                                       class="form-control <?php if (is_invalid("reg_password")) { ?> is-invalid <?php } ?>">

                                <?php if (is_invalid("reg_password")) { ?>
                                    <div class="form-text mb-3 invalid-feedback d-block">
                                        <?= get_error_message("reg_password") ?>
                                    </div>
                                <?php } ?>
                            </div>

                            <div class="mb-3">
                                <label for="reg-confirmed-password" class="form-label">
                                    <span class="text-primary h5">*</span>
                                    <span>Повторите пароль</span>
                                </label>
                                <input type="password" id="reg-confirmed-password" name="confirmed_password"
                                       class="form-control <?php if (is_invalid("reg_confirmed_password")) { ?> is-invalid <?php } ?>">

                                <?php if (is_invalid("reg_confirmed_password")) { ?>
                                    <div class="form-text mb-3 invalid-feedback d-block">
                                        <?= get_error_message("reg_confirmed_password") ?>
                                    </div>
                                <?php } ?>
                            </div>

                            <div>
                                <label for="reg-avatar" class="form-label">Загрузите изображение профиля</label>
                                <input type="file" accept="image/png,image/jpeg" id="reg-avatar" name="avatar"
                                       aria-describedby="upload-help"
                                       class="form-control <?php if (is_invalid("reg_avatar")) { ?> is-invalid <?php } ?>">

                                <?php if (is_invalid("reg_avatar")) { ?>
                                    <div class="form-text mb-3 invalid-feedback d-block">
                                        <?= get_error_message("reg_avatar") ?>
                                    </div>
                                <?php } else { ?>
                                    <div id="upload-help" class="form-text mb-3 ms-1">
                                        В формате jpg/png. Не более 5 Мб.
                                    </div>
                                <?php } ?>
                            </div>
                        </div>

                        <div id="info-help" class="form-text mb-3">
                            <span class="text-primary h5">*</span>
                            <span>- обязательные поля</span>
                        </div>

                        <?php if (is_invalid("exist_reg_email")) { ?>
                            <div class="d-flex justify-content-center invalid-feedback mb-3">
                                <?= get_error_message("exist_reg_email") ?>
                            </div>
                        <?php } ?>

                        <div class="justify-content-center row">
                            <button type="submit" class="btn btn-outline-primary col-5">Отправить</button>
                        </div>
                    </form>

                    <div class="mt-2 d-flex justify-content-center">
                        <a href="<?= AUTHORIZATION_PATH ?>"
                           class="link-secondary link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover"
                        >У меня уже есть аккаунт</a>
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
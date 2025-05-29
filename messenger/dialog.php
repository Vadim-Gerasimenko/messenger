<?php
require_once "constants/configure_constants.php";
require_once "constants/paths.php";

$page_title = SITE_NAME;

require_once "blocks/header.php";
require_once "actions/authorization_check.php";

?>

<div class="container">
    <div class="card col-12 col-md-10 mx-auto">
        <div class="card-header">
            <div class="d-inline-block">
                <div class="d-flex align-items-center">
                    <button type="button" class="btn btn-light btn-lg me-2" id="back-to-index-button">
                        <i class="bi bi-arrow-left"></i>
                    </button>
                </div>
            </div>
            <div class="d-inline-block pt-1 align-bottom">
                <div class="d-flex align-items-center" id="user-info">
                    <img src="<?= '/' . DEFAULT_AVATAR_PATH ?>" class="rounded-circle me-2 avatar"
                         width="50px" height="50px" alt="avatar">
                    <div>
                        <div>
                            <span class="name fw-semibold"></span>
                            <span class="surname fw-semibold"></span>
                        </div>
                        <div class="text-muted small" id="online-status">online</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="overflow-scroll mb-3 p-4 messenger-content" id="dialog-content">
                <div class="align-content-center fw-semibold text-secondary no-messages-info d-flex justify-content-center d-none">
                    Сообщения отсутствуют.
                </div>
            </div>
        </div>
        <div class="card-footer" id="dialog-input-area">
            <form class="row flex-nowrap align-items-center m-4" id="input-msg-form">
                <div class="col-auto px-2">
                    <button type="button" class="btn btn-light btn-lg" id="dropdownMenuLink" data-bs-toggle="dropdown"
                            aria-expanded="false">
                        <i class="bi bi-paperclip text-secondary"></i>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                        <li>
                            <button class="dropdown-item" id="image-btn">
                                <i class="bi bi-image me-1 text-secondary"></i>Фото
                            </button>
                        </li>
                        <li>
                            <button class="dropdown-item" id="video-btn">
                                <i class="bi bi-camera-video me-1 text-secondary"></i>Видео
                            </button>
                        </li>
                        <li>
                            <button class="dropdown-item" id="file-btn">
                                <i class="bi bi-file-earmark me-1 text-secondary"></i>Файл
                            </button>
                        </li>
                    </ul>
                </div>
                <input type="file" id="file-input" name="files" class="d-none" multiple>

                <div class="col px-2">
                    <textarea class="form-control form-control-lg messenger-text-area" rows="2" id="input-msg-text"
                              placeholder="Введите сообщение"></textarea>
                </div>

                <div class="col-auto px-2">
                    <button type="submit" class="btn btn-outline-primary border-0 btn-lg" id="send-msg-btn">
                        <i class="bi bi-send"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="files-sending-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Подтвердите отправку</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                </div>
                <div class="modal-footer">
                    <form class="input-group" id="input-files-form">
                    <textarea class="form-control messenger-text-area rounded-end-2 me-2" id="input-msg-with-files-text"
                              rows="1" placeholder="Введите сообщение"></textarea>
                        <button type="submit" class="btn btn-outline-primary border-0 rounded-start-2">
                            <i class="bi bi-send"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="/assets/js/dialog.js"></script>

<?php require_once "blocks/footer.php"; ?>
</body>
</html>
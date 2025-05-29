<?php
require_once "constants/configure_constants.php";
$page_title = SITE_NAME . ". Диалоги";

require_once "blocks/header.php";
require_once "actions/authorization_check.php";
?>

<main>
    <div class="container vh-100">
        <div class="card col-12 col-md-10 mx-auto">
            <div class="card-header">
                <div class="col-10 col-md-9 col-xxl-7 mx-auto">
                    <form class="navbar-form" id="search-form">
                        <div class="input-group">
                            <input type="text" class="form-control rounded-start-1"
                                   placeholder="Введите данные..." name="search"
                                   id="search-input">
                            <button id="search-form-submit-button" class="btn btn-primary" type="submit">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush" id="pen-pals-list">
                    <li class="d-flex justify-content-center d-none" id="no-dialogs-message">
                        Диалоги отсутствуют.
                    </li>
                </ul>
            </div>
        </div>
    </div>
</main>

<script src="/assets/js/index.js"></script>

<?php require_once "blocks/footer.php"; ?>
</body>
</html>
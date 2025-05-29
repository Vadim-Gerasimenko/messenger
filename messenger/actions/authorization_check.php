<?php
if (!is_auth()) {
    redirect(AUTHORIZATION_PATH);
}

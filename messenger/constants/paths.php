<?php
const SERVER_ROOT_DIR = "/Applications/MAMP/htdocs/";
const ASSETS_DIR = "assets/";
const ASSETS_DATA_DIR = ASSETS_DIR . "data/";

const REL_ICONS_DIR = "icons/";
const REL_IMAGES_DIR = "images/";
const REL_VIDEO_DIR = "videos/";
const REL_FILES_DIR = "files/";

const REL_AVATARS_DIR = "avatars/";

const HOMEPAGE_DIR = "messenger/";
const HOMEPAGE_PATH = "index.php";

const PROJECT_DIR = SERVER_ROOT_DIR;

const ACTIONS_DIR = "actions/";

const SERVICE_DIR = ASSETS_DATA_DIR . "service/";
const USERS_DIR = ASSETS_DATA_DIR . "users/";
const MESSAGES_DIR = ASSETS_DATA_DIR . "messages/";

const SERVICE_UPLOADS_DIR = SERVICE_DIR . "uploads/";
const USERS_UPLOADS_DIR = USERS_DIR . "uploads/";
const MESSAGES_UPLOADS_DIR = MESSAGES_DIR . "uploads/";

const SERVICE_IMAGES_DIR = SERVICE_UPLOADS_DIR . REL_IMAGES_DIR;
const USERS_IMAGES_DIR = USERS_UPLOADS_DIR . REL_IMAGES_DIR;
const MESSAGES_IMAGES_DIR = MESSAGES_UPLOADS_DIR . REL_IMAGES_DIR;

const SERVICE_ICONS_DIR = SERVICE_UPLOADS_DIR . REL_ICONS_DIR;

const SERVICE_AVATARS_DIR = SERVICE_IMAGES_DIR . REL_AVATARS_DIR;
const USERS_AVATARS_DIR = USERS_IMAGES_DIR . REL_AVATARS_DIR;

const MESSAGES_VIDEO_DIR = MESSAGES_UPLOADS_DIR . REL_VIDEO_DIR;
const MESSAGES_FILES_DIR = MESSAGES_UPLOADS_DIR . REL_FILES_DIR;

const REGISTRATION_PATH = "registration.php";
const AUTHORIZATION_PATH = "authorization.php";

const ERROR_500_PATH = "error_500.php";

const DB_CONNECTION_ACTION_PATH = ACTIONS_DIR . "db_connection.php";
const REGISTRATION_ACTION_PATH = ACTIONS_DIR . "registration.php";
const AUTHORIZATION_ACTION_PATH = ACTIONS_DIR . "authorization.php";
const LOGOUT_ACTION_PATH = ACTIONS_DIR . "logout.php";

const DEFAULT_ICON_LOGO_PATH = "http://localhost:8888/" . SERVICE_ICONS_DIR . "logo/default_icon.png";
const DEFAULT_AVATAR_PATH = SERVICE_AVATARS_DIR . "default_avatar.jpg";

const HELPER_FUNCTIONS_PATH = "functions/helpers.php";

const FULL_DOMAIN = "http://localhost:8888/";
const PROJECT_ADDRESS = FULL_DOMAIN . "messenger/";
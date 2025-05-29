<?php
function redirect(string $path): void
{
    header("Location: " . PROJECT_ADDRESS . $path);
    exit();
}

function is_admin(): bool
{
    return isset($_SESSION["is_admin"]) && $_SESSION["is_admin"];
}

function is_auth(): bool
{
    return isset($_SESSION["is_auth"]) && $_SESSION["is_auth"];
}

function is_error_500(): bool
{
    return isset($_SESSION["error500"]);
}

function sanitize_text($text): string
{
    return filter_var($text, FILTER_SANITIZE_SPECIAL_CHARS) ?? "";
}

function trim_sanitize_text($text): string
{
    return trim(filter_var($text, FILTER_SANITIZE_SPECIAL_CHARS)) ?? "";
}
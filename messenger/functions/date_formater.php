<?php
require_once "constants/configure_constants.php";
function format_date(string $date_string, string $pattern): string
{
    $intlFormatter = new IntlDateFormatter(
        FORMAT_LOCALE,
        IntlDateFormatter::RELATIVE_SHORT,
        IntlDateFormatter::SHORT
    );

    $intlFormatter->setPattern($pattern);

    $date = DateTime::createFromFormat(DATE_TIME_FORMAT, $date_string);
    return $intlFormatter->format($date);
}
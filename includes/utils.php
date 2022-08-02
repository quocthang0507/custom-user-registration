<?php

/**
 * Formats a date string saved in database (d/m/Y) to ISO8601 yyyy-mm-dd
 */
function dmy2ymd($date_string_in_db)
{
    try {
        if ($date_string_in_db == null)
            return $date_string_in_db;
        $date = DateTime::createFromFormat('d/m/Y', $date_string_in_db);
        return $date->format('Y-m-d');
    } catch (Exception $e) {
        return $date_string_in_db;
    }
}

/**
 * Formats a datestring yyyy-mm-dd to d/m/Y
 */
function ymd2dmy($date_string_in_html)
{
    try {
        if ($date_string_in_html == null)
            return $date_string_in_html;
        $date = DateTime::createFromFormat('Y-m-d', $date_string_in_html);
        return $date->format('d/m/Y');
    } catch (Exception $e) {
        return $date_string_in_html;
    }
}

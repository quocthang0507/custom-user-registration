<?php

/**
 * Utilities
 * 
 * @version  1.0.0
 * @package CustomUserRegistration
 */

require_once UR_PLUGIN_MODELS_DIR . '/Constants.php';

/**
 * Returns a new string from datetime
 */
function date_to_string(string $rfc_3339)
{
    try {
        $datetime = strtotime($rfc_3339);
        return date(vi_datetime, $datetime);
    } catch (Exception $e) {
        return $rfc_3339;
    }
}

/**
 * Get current url
 */
function get_current_url()
{
    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
        $url = "https://";
    else
        $url = "http://";
    // Append the host(domain name, ip) to the URL.   
    $url .= $_SERVER['HTTP_HOST'];
    // Append the requested resource location to the URL   
    $url .= $_SERVER['REQUEST_URI'];

    return $url;
}

function get_website_domain()
{
    global $_SERV‌​ER;
    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
        $url = "https://";
    else
        $url = "http://";
    $url .= $_SERVER['SERVER_NAME'] . dirname($_SERVER['PHP_SELF']);
    return $url;
}

/**
 * Get data from an ajax request
 */
function parse_input_ajax()
{
    $data = file_get_contents("php://input");
    if ($data == false)
        return array();
    parse_str($data, $result);
    return $result;
}

/**
 * Get parameter from a url string
 */
function parse_url_param(string $url, string $param)
{
    $url_components = parse_url($url);
    if (isset($url_components['query'])) {
        parse_str($url_components['query'], $params);
        return $params[$param];
    }
    return null;
}

/**
 * Get all administrator accounts in wordpress
 */
function get_all_administrators()
{
    $args = array(
        'role__in' => array('administrator')
    );
    return get_users($args);
}

/**
 * Remove duplicate from array of strings without case sensitive
 */
function array_unique_incasesensitive(array $array_of_string)
{
    return array_intersect_key($array_of_string, array_unique(array_map('strtolower', $array_of_string)));
}

/**
 * Sort an array of strings without case sensitive
 */
function sort_incasesensitive(array &$array_of_string)
{
    usort($array_of_string, 'strnatcasecmp');
}

function last_name_compare(string $full_name_a, string $full_name_b)
{
    // Split string by ' ' and get the last word
    $a = explode(' ', $full_name_a);
    $b = explode(' ', $full_name_b);
    $last_a = end($a);
    $last_b = end($b);
    return strcasecmp($last_a, $last_b);
}

/**
 * Sort an array of full name
 */
function sort_incasesensitive_lastname(array &$array_of_fullname)
{
    uasort($array_of_fullname, 'last_name_compare');
}

/**
 * Check string null or whitespace
 */
function is_null_or_whitespace(string $str)
{
    return $str === null || trim($str) === '';
}

/**
 * Check array null or whitespace
 */
function is_one_null_or_whitespace(...$array_of_str)
{
    foreach ($array_of_str as $str) {
        if (is_null_or_whitespace($str))
            return true;
    }
    return false;
}

/**
 * Read csv file with multiple lines in a column
 */
function read_csv(string $file_name)
{
    $csv = array();
    if (($handle = fopen($file_name, 'r')) !== false) {
        while (($row = fgetcsv($handle, 0, ",")) !== false) {
            array_push($csv, $row);
        }
        fclose($handle);
    }
    return $csv;
}

/**
 * Đổi chuỗi có dấu thành không dấu
 */
function remove_vi_diacritics($str)
{
    $unicode = array(
        'a' => 'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ',
        'd' => 'đ',
        'e' => 'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',
        'i' => 'í|ì|ỉ|ĩ|ị',
        'o' => 'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',
        'u' => 'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',
        'y' => 'ý|ỳ|ỷ|ỹ|ỵ',
        'A' => 'Á|À|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ẳ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',
        'D' => 'Đ',
        'E' => 'É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',
        'I' => 'Í|Ì|Ỉ|Ĩ|Ị',
        'O' => 'Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',
        'U' => 'Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',
        'Y' => 'Ý|Ỳ|Ỷ|Ỹ|Ỵ',
    );

    foreach ($unicode as $nonUnicode => $uni) {
        $str = preg_replace("/($uni)/i", $nonUnicode, $str);
    }
    $str = str_replace(' ', '_', $str);
    return $str;
}

function is_not_null($var)
{
    return isset($var) && !empty($var);
}

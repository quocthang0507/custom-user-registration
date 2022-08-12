<?php

/**
 * Utilities
 * 
 * @version  1.0.0
 * @package CustomUserRegistration
 */

/**
 * Returns a new string from datetime
 */
function date_to_string(string $rfc_3339)
{
    try {
        $datetime = strtotime($rfc_3339);
        return date('d/m/Y H:i:s', $datetime);
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
    parse_str($url_components['query'], $params);
    return $params[$param];
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
function array_unique_incasesensitive($array_of_string)
{
    return array_intersect_key($array_of_string, array_unique(array_map('strtolower', $array_of_string)));
}

/**
 * Sort an array of strings without case sensitive
 */
function sort_incasesensitive($array_of_string)
{
    usort($array_of_string, 'strnatcasecmp');
}

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
        return date('d/m/Y h:i:s A', $datetime);
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
function parse_input()
{
    $data = file_get_contents("php://input");
    if ($data == false)
        return array();
    parse_str($data, $result);
    return $result;
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

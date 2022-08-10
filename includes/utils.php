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
function date_to_string($rfc_3339)
{
    try {
        $datetime = strtotime($rfc_3339);
        return date('d/m/Y h:i:s A', $datetime);
    } catch (Exception $e) {
        return $rfc_3339;
    }
}

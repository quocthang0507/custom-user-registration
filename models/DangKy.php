<?php

/**
 * Class DangKy
 * 
 * @version  1.0.0
 * @package CustomUserRegistration
 */

require_once 'Constants.php';

/**
 * Metadata for users
 */
class ur_DangKy
{
    public int $ID_do_an;
    public string $registered_date;

    public function __construct(int $id_do_an, string $registered_date)
    {
        $this->ID_do_an = $id_do_an;
        $this->registered_date = $registered_date;
    }

    public function update_user_registration(int $user_id)
    {
        update_user_meta($user_id);
    }
}

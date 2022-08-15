<?php

/**
 * Class DangKy
 * 
 * @version  1.0.0
 * @package CustomUserRegistration
 */

require_once 'Constants.php';

/**
 * Metadata for DoAn post type
 */
class ur_DangKy
{
    public string $registered_date;
    public string $registered_user_id;

    public static function register(string $user_id, string $post_id)
    {
        $dang_ky = new ur_DangKy();
        $dang_ky->registered_date = date('Y-m-d\TH:i');
        $dang_ky->registered_user_id = $user_id;

        $registered_students = get_post_meta($post_id, UR_REGISTER_DO_AN_META_KEY, true);
        // Nếu chưa đăng ký hoặc danh sách trống
        if ($registered_students == null) {
            $registered_students = array();
            array_push($registered_students, $dang_ky);
        }
        // Nếu đã có người đăng ký thì không cho đăng ký 2 lần trên cùng 1 đồ án

        // Và không được đăng ký nhiều đồ án cùng loại (cơ sở hoặc chuyên ngành) trong cùng một học kỳ

        update_post_meta($post_id, UR_REGISTER_DO_AN_META_KEY, $registered_students);
    }

    /**
     * Return number of registration
     */
    public static function get_count_registration_by_id(int $post_id)
    {
        $registered_students = get_post_meta($post_id, UR_REGISTER_DO_AN_META_KEY, true);
        if ($registered_students != null && is_array($registered_students))
            return count($registered_students);
        return 0;
    }

    public static function is_user_already_registered(string $user_id, string $post_id)
    {
        $registered_students = get_post_meta($post_id, UR_REGISTER_DO_AN_META_KEY, true);
        if ($registered_students == null || !is_array($registered_students))
            return false;
        foreach ($registered_students as $registration) {
            if ($registration->registered_user_id == $user_id)
                return true;
        }
        return false;
    }

    public static function is_user_registered_elsewhere(string $user_id, string $type)
    {
    }
}

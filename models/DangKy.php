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
    public int $registered_user_id;

    /**
     * User registers a đồ án
     */
    public static function register(int $user_id, int $post_id)
    {
        $do_an = ur_DoAn::get_do_an_by_id($post_id);
        // Đồ án không thể đăng ký
        if (!$do_an->is_available())
            return false;

        $dang_ky = new ur_DangKy();
        $dang_ky->registered_date = date('Y-m-d\TH:i');
        $dang_ky->registered_user_id = $user_id;

        $registered_students = get_post_meta($post_id, UR_REGISTER_DO_AN_META_KEY, true);
        $type = get_post_meta($post_id, UR_DO_AN . '_type', true);
        // Nếu đã đăng ký thì không cho đăng ký 2 lần trên cùng 1 đồ án
        // Và không được đăng ký nhiều đồ án cùng loại (cơ sở hoặc chuyên ngành) trong cùng một học kỳ
        if (!self::is_user_registered_elsewhere($user_id, $type)) {
            // Nếu chưa đăng ký hoặc danh sách trống
            if (is_null($registered_students)) {
                $registered_students = array();
            }
            array_push($registered_students, $dang_ky);
            update_post_meta($post_id, UR_REGISTER_DO_AN_META_KEY, $registered_students);
            return true;
        }
        return false;
    }

    /**
     * User unregisters a đồ án
     */
    public static function unregister(int $user_id, int $post_id)
    {
        $registered_students = get_post_meta($post_id, UR_REGISTER_DO_AN_META_KEY, true);
        if ($registered_students != null && is_array($registered_students)) {
            foreach ($registered_students as $index => $registration) {
                if ($registration->registered_user_id == $user_id) {
                    unset($registered_students[$index]);
                    break;
                }
            }
            update_post_meta($post_id, UR_REGISTER_DO_AN_META_KEY, $registered_students);
        }
    }

    /**
     * Return number of registrations
     */
    public static function get_count_registration_by_id(int $post_id)
    {
        $registered_students = get_post_meta($post_id, UR_REGISTER_DO_AN_META_KEY, true);
        if ($registered_students != null && is_array($registered_students))
            return count($registered_students);
        return 0;
    }

    /**
     * Is user already registered this đồ án?
     */
    public static function is_user_already_registered(int $user_id, int $post_id)
    {
        $registered_students = get_post_meta($post_id, UR_REGISTER_DO_AN_META_KEY, true);
        if (is_null($registered_students) || !is_array($registered_students))
            return false;
        foreach ($registered_students as $registration) {
            if ($registration->registered_user_id == $user_id)
                return true;
        }
        return false;
    }

    /**
     * Is user already đồ án in elsewhere?
     */
    public static function is_user_registered_elsewhere(int $user_id, string $type)
    {
        $list_do_an = ur_DoAn::get_list_do_an_available($type, true); // Lấy danh sách đồ án chỉ có id và tên trong thời gian đăng ký
        foreach ($list_do_an as $do_an) {
            if (self::is_user_already_registered($user_id, $do_an->ID))
                return true;
        }
        return false;
    }

    /**
     * Return a list of user registerd đồ án
     */
    public static function get_list_registered_students(int $post_id)
    {
        $registered_student_ids = get_post_meta($post_id, UR_REGISTER_DO_AN_META_KEY, true);
        if (is_null($registered_student_ids) || !is_array($registered_student_ids))
            $registered_students = array();
        foreach ($registered_student_ids as $user_id) {
            $user = get_user_by('ID', $user_id);
            array_push($registered_students, $user);
        }
        return $registered_students;
    }
}

<?

/**
 * Class: DoAnInfo
 * 
 * @version  1.0.0
 * @package CustomUserRegistration
 */

require_once UR_PLUGIN_MODELS_DIR . '/Constants.php';

class ur_Info
{
    public static function add_instructor($name)
    {
        $current_id = get_current_user_id();
        if ($name != null && is_string($name)) {
            $instructors = get_user_meta($current_id, UR_INSTRUCTORS_META_KEY, true);
            if ($instructors == null)
                $instructors = array();
            array_push($instructors, $name);

            update_user_meta($current_id, UR_INSTRUCTORS_META_KEY, $instructors);
            return true;
        }
        return false;
    }

    /**
     * Lấy danh sách GVHD từ metadata của users
     */
    public static function get_all_instructors()
    {
        $users = get_users(array('fields' => array('ID')));
        $instructors = array();
        foreach ($users as $user) {
            $result = get_user_meta($user->ID, UR_INSTRUCTORS_META_KEY, true);
            if ($result != null) {
                $instructors = array_merge($instructors, $result);
            }
        }
        return $instructors;
    }
}

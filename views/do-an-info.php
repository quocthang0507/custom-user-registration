<?php

/**
 * Admin view: Quản lý lớp học và giảng viên hướng dẫn
 * 
 * @version  1.0.0
 * @package CustomUserRegistration
 */

require_once UR_PLUGIN_MODELS_DIR . '/Constants.php';

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

function add_instructor($name)
{
    $current_id = get_current_user_id();
    if ($name != null || is_string($name)) {
        update_user_meta($current_id, UR_INSTRUCTORS_META_KEY, $name);
    }
}

/**
 * Lấy danh sách GVHD từ metadata của users
 */
function get_all_instructors()
{
    $users = get_users(array('fields' => array('ID')));
    $instructors = array();
    foreach ($users as $user) {
        $result = get_user_meta($user->ID, UR_INSTRUCTORS_META_KEY);
        array_push($instructors, $result);
    }
    return $instructors;
}

?>

<div class="wrap">
    <h1 class="wp-heading-inline">Quản lý lớp học và giảng viên hướng dẫn</h1>
    <hr class="wp-header-end">
    <p>
        <i>Tên lớp và tên giảng viên hướng dẫn sẽ xuất hiện trong các menu và đồ án</i>
    </p>
    <div class="m-2">
        <div class="row">
            <div class="col-5">
                <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <div class="row mb-2">
                        <label for="add_instructor" class="col-auto col-form-label">Giảng viên hướng dẫn</label>
                        <div class="col-5">
                            <input type="text" name="add_instructor" class="form-control" id="add_instructor" placeholder="Thêm GVHD">
                        </div>
                        <div class="col-auto">
                            <input type="submit" class="btn btn-primary" value="Thêm">
                        </div>
                    </div>
                </form>
                <div class="row mb-2">
                    <label for="list-instructorr" class="form-label">Danh sách GVHD</label>
                    <div class="list-group" id="list-instructor">
                        <a href="#" class="list-group-item list-group-item-action">A second link item</a>
                        <a href="#" class="list-group-item list-group-item-action">A third link item</a>
                        <a href="#" class="list-group-item list-group-item-action">A fourth link item</a>
                    </div>
                </div>
            </div>
            <div class="col-2">
            </div>
            <div class="col-5">
                <div class="list-group">
                    <a href="#" class="list-group-item list-group-item-action">A second link item</a>
                    <a href="#" class="list-group-item list-group-item-action">A third link item</a>
                    <a href="#" class="list-group-item list-group-item-action">A fourth link item</a>
                </div>
            </div>
        </div>
    </div>
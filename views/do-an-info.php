<?php

/**
 * Admin view: Quản lý lớp học và giảng viên hướng dẫn
 * 
 * @version  1.0.0
 * @package CustomUserRegistration
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

require_once UR_PLUGIN_MODELS_DIR . '/Constants.php';
require_once UR_PLUGIN_MODELS_DIR . '/Info.php';

$error = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_instructor'])) {
    $instructor = $_POST['add_instructor'];
    $error = !ur_Info::add_instructor($instructor);
}


$list_instructors = ur_Info::get_all_instructors();

?>

<div class="wrap">
    <h1 class="wp-heading-inline">Quản lý lớp học và giảng viên hướng dẫn</h1>
    <hr class="wp-header-end">
    <p>
        <i>Tên lớp và tên giảng viên hướng dẫn sẽ xuất hiện trong các menu và đồ án</i>
    </p>
    <?php
    if ($error)
        echo '<div id="message" class="notice notice-error is-dismissible"><p>Đã có lỗi xảy ra khi thêm vào CSDL.</p></div>';
    ?>
    <div class="m-2">
        <div class="row">
            <div class="col-6 border border-end-0">
                <div class="row m-2">
                    <form method="post" action="<?php echo $_SERVER['PHP_SELF'] . '?post_type=' . UR_DO_AN . '&page=' . UR_DO_AN . '-info'; ?>">
                        <div class="row">
                            <label for="add_instructor" class="col-auto col-form-label">Giảng viên hướng dẫn</label>
                            <div class="col-5">
                                <input type="text" name="add_instructor" class="form-control" id="add_instructor" placeholder="Thêm GVHD">
                            </div>
                            <div class="col-auto">
                                <input type="submit" class="btn btn-primary" value="Thêm">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="row m-2">
                    <label for="list-instructor" class="form-label">Danh sách GVHD</label>
                    <ol class="list-group list-group-numbered" id="list-instructor">
                        <?php
                        if (count($list_instructors) == 0)
                            echo '<li class="list-group-item d-flex justify-content-between align-items-start"><div class="ms-2 me-auto">Danh sách trống</div></li>';
                        else
                            foreach ($list_instructors as $instructor) {
                        ?>
                            <li class="list-group-item d-flex justify-content-between align-items-start">
                                <div class="ms-2 me-auto">
                                    <?php echo $instructor; ?>
                                </div>
                                <span class="badge bg-danger rounded-pill">&times;</span>
                            </li>
                        <?php
                            }
                        ?>
                    </ol>
                </div>
            </div>
            <div class="col-6 border">
                <div class="list-group m-2">
                    <a href="#" class="list-group-item list-group-item-action">A second link item</a>
                    <a href="#" class="list-group-item list-group-item-action">A third link item</a>
                    <a href="#" class="list-group-item list-group-item-action">A fourth link item</a>
                </div>
            </div>
        </div>
    </div>
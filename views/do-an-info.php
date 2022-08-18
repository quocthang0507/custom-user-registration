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
require_once UR_PLUGIN_INCLUDES_DIR . '/utils.php';
require_once UR_PLUGIN_MODELS_DIR . '/Info.php';

$error = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_instructor'])) {
        $instructor = trim($_POST['add_instructor']);
        $error = !ur_Info::add_instructor($instructor);
    } else if (isset($_POST['add_class'])) {
        $class = trim($_POST['add_class']);
        $error = !ur_Info::add_class($class);
    }
} else if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    $data = parse_input_ajax();
    $id = isset($data['delete_id']) ? $data['delete_id'] : '';
    if (str_starts_with($id, 'gvhd')) {
        $id = str_replace('gvhd', '', $id);
        ur_Info::delete_instructor($id);
    } else if (str_starts_with($id, 'lop')) {
        $id = str_replace('lop', '', $id);
        ur_Info::delete_class($id);
    }
    http_response_code(200);
    exit(0);
}

$list_instructors = ur_Info::get_all_instructors();
$list_classes = ur_Info::get_all_classes();

?>

<div class="wrap">
    <h1 class="wp-heading-inline">Quản lý lớp học và giảng viên hướng dẫn</h1>
    <hr class="wp-header-end">
    <p>
        <i>Tên lớp và tên giảng viên hướng dẫn sẽ xuất hiện khi tạo đồ án.</i>
        <i>Thêm hoặc xóa các thông tin bên dưới <u>không ảnh hưởng</u> đến các đồ án đã tạo.</i>
    </p>
    <?php
    if ($error)
        echo '<div id="message" class="notice notice-error is-dismissible"><p>Đã có lỗi xảy ra khi thêm vào CSDL.</p></div>';
    ?>
    <div class="m-2">
        <div class="row">
            <!--GVHD-->
            <div class="col-6 border border-end-0">
                <div class="row m-2">
                    <form method="post" action="<?php echo get_current_url(); ?>">
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
                    <label for="list-instructors" class="form-label">Danh sách GVHD</label>
                    <ol class="list-group list-group-numbered overflow-auto" id="list-instructors">
                        <?php
                        if (count($list_instructors) == 0)
                            echo '<li class="list-group-item d-flex justify-content-between align-items-start"><div class="ms-2 me-auto">Danh sách trống</div></li>';
                        else
                            foreach ($list_instructors as $index => $instructor) {
                        ?>
                            <li class="list-group-item d-flex justify-content-between align-items-start">
                                <div class="ms-2 me-auto" id="gvhd<?php echo $index; ?>">
                                    <?php echo $instructor; ?>
                                </div>
                                <span class="badge bg-danger rounded-pill btn btnDelete" data-bs-toggle="tooltip" data-bs-title="Xóa" id="btnDelete_gvhd<?php echo $index; ?>">&times;</span>
                            </li>
                        <?php
                            }
                        ?>
                    </ol>
                </div>
            </div>

            <!--Lớp-->
            <div class="col-6 border">
                <div class="row m-2">
                    <form method="post" action="<?php echo $_SERVER['PHP_SELF'] . '?post_type=' . UR_DO_AN . '&page=' . UR_DO_AN . '-info'; ?>">
                        <div class="row">
                            <label for="add_class" class="col-auto col-form-label">Lớp</label>
                            <div class="col-5">
                                <input type="text" name="add_class" class="form-control" id="add_class" placeholder="Thêm lớp">
                            </div>
                            <div class="col-auto">
                                <input type="submit" class="btn btn-primary" value="Thêm">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="row m-2">
                    <label for="list-classes" class="form-label">Danh sách lớp</label>
                    <ol class="list-group list-group-numbered overflow-auto" id="list-instructors">
                        <?php
                        if (count($list_classes) == 0)
                            echo '<li class="list-group-item d-flex justify-content-between align-items-start"><div class="ms-2 me-auto">Danh sách trống</div></li>';
                        else
                            foreach ($list_classes as $index => $class) {
                        ?>
                            <li class="list-group-item d-flex justify-content-between align-items-start">
                                <div class="ms-2 me-auto" id="lop<?php echo $index; ?>">
                                    <?php echo $class; ?>
                                </div>
                                <span class="badge bg-danger rounded-pill btn btnDelete" data-bs-toggle="tooltip" data-bs-title="Xóa" id="btnDelete_lop<?php echo $index; ?>">&times;</span>
                            </li>
                        <?php
                            }
                        ?>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            $('.btnDelete').click(function(e) {
                let id = e.target.id.split('_')[1];
                let url = '<?php echo get_current_url(); ?>';
                let text = $('#' + id).text().trim();
                let msg = 'Bạn có muốn xóa phần tử có giá trị \'' + text + '\' ra khỏi CSDL không?';
                if (confirm(msg)) {
                    $.ajax({
                        url: url,
                        type: 'DELETE',
                        data: {
                            delete_id: id
                        },
                        success: function(response) {
                            location.reload();
                        },
                        error: function(error) {
                            console.error(error);
                        }
                    });
                }
            });
        });
    </script>
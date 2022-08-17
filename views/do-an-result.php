<?php

/**
 * Admin view: Kết quả đăng ký đồ án
 * 
 * @version  1.0.0
 * @package CustomUserRegistration
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

require_once UR_PLUGIN_MODELS_DIR . '/Constants.php';
require_once UR_PLUGIN_MODELS_DIR . '/Info.php';
require_once UR_PLUGIN_INCLUDES_DIR . '/utils.php';

$list_instructors = ur_Info::get_all_instructors();
$list_classes = ur_Info::get_all_classes();
$list_do_an = ur_DoAn::get_list_do_an();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    $instructor = isset($_POST[UR_DO_AN . '_instructor']) ? $_POST[UR_DO_AN . '_instructor'] : 'all';
    $type = isset($_POST[UR_DO_AN . '_type']) ? $_POST[UR_DO_AN . '_type'] : 'all';
    $class = isset($_POST[UR_DO_AN . '_class']) ? $_POST[UR_DO_AN . '_class'] : 'all';
    $semester = isset($_POST[UR_DO_AN . '_semester']) ? $_POST[UR_DO_AN . '_semester'] : 'all';
    $schoolyear = isset($_POST[UR_DO_AN . '_schoolyear']) ? $_POST[UR_DO_AN . '_schoolyear'] : 'all';
    $list_do_an = ur_DoAn::get_list_do_an($type, $instructor, $schoolyear, $semester, $class);
}

?>

<div class="wrap">
    <h1 class="wp-heading-inline">Danh sách đăng ký đồ án</h1>

    <div class="row">
        <div class="col-md-4 col-lg-4">
            <div class="card">
                <form method="POST" action="<?php echo get_current_url(); ?>" enctype="multipart/form-data">
                    Tìm kiếm theo:
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <label class="form-label">Giảng viên hướng dẫn:</label>
                                <select class="form-control" name="<?php echo UR_DO_AN; ?>_instructor" aria-label="GVHD" title="GVHD" id="cbxInstructor">
                                    <option value="all">Tất cả</option>
                                    <?php
                                    foreach ($list_instructors as $item) {
                                        if (isset($instructor) && $instructor == $item)
                                            echo '<option value="' . $item . '" selected>' . $item . '</option>';
                                        else
                                            echo '<option value="' . $item . '">' . $item . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-lg-6">
                                <label class="form-label">Loại đồ án:</label>
                                <select class="form-control" name="<?php echo UR_DO_AN; ?>_type" aria-label="Loại" title="Loại" id="cbxType">
                                    <option value="all">Tất cả</option>
                                    <?php
                                    $selected_dacs = isset($type) && $type == DO_AN_CO_SO ? 'selected' : '';
                                    $selected_dacn = isset($type) && $type == DO_AN_CHUYEN_NGANH ? 'selected' : '';
                                    echo '<option value="' . DO_AN_CO_SO . '" ' . $selected_dacs . '>Đồ án cơ sở</option>';
                                    echo '<option value="' . DO_AN_CHUYEN_NGANH . '" ' . $selected_dacn . '>Đồ án chuyên ngành</option>';
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4">
                                <label class="form-label">Lớp:</label>
                                <select class="form-control" name="<?php echo UR_DO_AN; ?>_class" aria-label="Lớp" title="Lớp" id="cbxClass">
                                    <option value="all" selected>Tất cả</option>
                                    <?php
                                    foreach ($list_classes as $item) {
                                        if (isset($class) && $class == $item)
                                            echo '<option value="' . $item . '" selected>' . $item . '</option>';
                                        else
                                            echo '<option value="' . $item . '">' . $item . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-lg-4">
                                <label class="form-label">Năm học</label>
                                <select class="form-control" name="<?php echo UR_DO_AN; ?>_schoolyear" aria-label="Năm học" title="Năm học" id="cbxSchoolyear">
                                    <option value="all" selected>Tất cả</option>
                                    <?php
                                    $year = date('Y');
                                    for ($i = 0; $i < 10; $i++) {
                                        $value = $year - $i . '-' . $year - $i + 1;
                                        if (isset($schoolyear) && $schoolyear == $value)
                                            echo '<option value="' . $value . '" selected>' . $value . '</option>';
                                        else
                                            echo '<option value="' . $value . '">' . $value . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-lg-4">
                                <label class="form-label">Học kỳ:</label>
                                <select class="form-control" name="<?php echo UR_DO_AN; ?>_semester" aria-label="Học kỳ" title="Học kỳ" id="cbxSemester">
                                    <option value="all">Tất cả</option>
                                    <?php
                                    $selected_hk1 = isset($semester) && $semester == 'HK1' ? 'selected' : '';
                                    $selected_hk2 = isset($semester) && $semester == 'HK2' ? 'selected' : '';
                                    $selected_hk3 = isset($semester) && $semester == 'HK3' ? 'selected' : '';
                                    ?>
                                    <option value="HK1" <?php echo $selected_hk1; ?>>HK1</option>
                                    <option value="HK2" <?php echo $selected_hk2; ?>>HK2</option>
                                    <option value="HK3" <?php echo $selected_hk3; ?>>HK3</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="text-center">
                        <input type="button" name="reset" value="Tất cả" class="btn btn-secondary btn-sm m-1" id="btnReset">
                        <input type="submit" name="submit" value="Tìm kiếm" class="btn btn-primary btn-sm m-1" id="btnSubmit">
                        <input type="button" name="export" value="Xuất danh sách đăng ký" class="btn btn-success btn-sm m-1">
                    </div>
                </form>
            </div>
        </div>
        <div class="col-md-8 col-lg-8">
            <div>
                <div class="text-center mt-4">
                    <h3>DANH SÁCH ĐỒ ÁN</h3>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover caption-top">
                        <caption>Danh sách này có <?php echo count($list_do_an); ?> đề tài</caption>
                        <thead>
                            <tr class="align-middle text-center">
                                <th scope="col">STT</th>
                                <th scope="col">Loại</th>
                                <th scope="col">Tên đề tài</th>
                                <th scope="col">Mô tả/yêu cầu đề tài</th>
                                <th scope="col">Giảng viên hướng dẫn</th>
                                <th scope="col">Tài liệu tham khảo</th>
                                <th scope="col">Trạng thái</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($list_do_an as $index => $do_an) {
                                $list_students = ur_DangKy::get_list_registered_students($do_an->ID);
                                echo '<tr class="align-middle">';
                                echo '<th scope="row" class="text-center">' . $index + 1 . '</th>';
                                echo '<td>' . ($do_an->type == DO_AN_CO_SO ? 'Đồ án cơ sở' : 'Đồ án chuyên ngành') . '</td>';
                                echo '<td>' . $do_an->post_title . '</td>';
                                echo '<td>' . $do_an->description . '</td>';
                                echo '<td class="text-center">' . $do_an->instructor . '</td>';
                                echo '<td>' . $do_an->references . '</td>';
                                echo '<td class="text-center">(' . $do_an->get_count_registration() . ' SV/' . $do_an->max_students . ' SV đã đăng ký)<br>';
                                echo '<ol class="list-group list-group-numbered">';
                                foreach ($list_students as $student) {
                                    echo '<li>' . $student->user_registration_student_id . '_' . $student->last_name . ' ' . $student->first_name . '</li>';
                                }
                                echo '</ol>';
                                echo '</td>';
                                echo '</tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    jQuery(document).ready(function($) {
        $('#btnReset').click(function() {
            $('#cbxInstructor').val('all');
            $('#cbxType').val('all');
            $('#cbxClass').val('all');
            $('#cbxSemester').val('all');
            $('#cbxSchoolyear').val('all');
            $('#btnSubmit').click();
        });
    });
</script>
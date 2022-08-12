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

$list_instructors = ur_Info::get_all_instructors();
$list_classes = ur_Info::get_all_classes();

?>

<div class="wrap">
    <h1 class="wp-heading-inline">Danh sách đăng ký đồ án</h1>

    <div class="card">
        <form method="POST" action="" enctype="multipart/form-data">
            Tìm kiếm theo:
            <div class="card-body">
                <div class="row mb-1">
                    <div class="col">
                        <label for="instructor" class="form-label">Giảng viên hướng dẫn:</label>
                        <select class="form-control" name="<?php echo UR_DO_AN; ?>_instructor" aria-label="GVHD" title="GVHD">
                            <option value="0">Tất cả</option>
                            <?php
                            foreach ($list_instructors as $item) {
                                echo '<option value="' . $item . '">' . $item . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col">
                        <label for="type" class="form-label">Loại đồ án:</label>
                        <select class="form-control" name="<?php echo UR_DO_AN; ?>_type">
                            <option value="0">Tất cả</option>
                            <option value="<?php echo DO_AN_CO_SO ?>">Đồ án cơ sở</option>
                            <option value="<?php echo DO_AN_CHUYEN_NGANH ?>">Đồ án chuyên ngành</option>
                        </select>
                    </div>
                </div>
                <div class="row mb-1">
                    <div class="col">
                        <label for="class" class="form-label">Lớp:</label>
                        <select class="form-control" name="<?php echo UR_DO_AN; ?>_class" aria-label="Lớp" title="Lớp">
                            <option value="0">Tất cả</option>
                            <?php
                            foreach ($list_classes as $item) {
                                echo '<option value="' . $item . '">' . $item . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col">
                        <label for="semester" class="form-label">Học kỳ:</label>
                        <select class="form-control" name="<?php echo UR_DO_AN; ?>_semester">
                            <option value="0">Tất cả</option>
                            <option value="HK1">HK1</option>
                            <option value="HK2">HK2</option>
                            <option value="HK3">HK3</option>
                        </select>
                    </div>
                </div>
            </div>
            <input type="submit" value="Tìm kiếm" class="btn btn-primary btn-sm">
        </form>
    </div>
</div>
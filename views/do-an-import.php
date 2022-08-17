<?php

/**
 * Admin view: Nhập danh sách đồ án từ tập tin
 * 
 * @version  1.0.0
 * @package CustomUserRegistration
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

require_once UR_PLUGIN_MODELS_DIR . '/Constants.php';
require_once UR_PLUGIN_MODELS_DIR . '/Info.php';

$list_classes = ur_Info::get_all_classes();

?>

<div class="wrap">
    <h1 class="wp-heading-inline">Nhập danh sách đồ án từ tập tin</h1>
    <hr class="wp-header-end">
    <ul class="unorder-list">
        <li>
            <p><i>Dữ liệu phải là tập tin .csv (comma-separated values), ngăn cách giữa hai cột là một dấu phẩy.</i></p>
        </li>
        <li>
            <p><i>Nên sử dụng định dạng <u>CSV UTF-8 (Comma delimited)</u> có trong Microsoft Excel.</i></p>
        </li>
        <li>
            <p><i>Tập tin đồ án phải tuân thủ thứ tự các cột, bao gồm cả tên cột và các dòng dữ liệu, không có dòng hay cột trống.</i></p>
        </li>
    </ul>
    <h6>Ví dụ về mẫu thông tin danh sách đồ án</h6>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th scope="col">Tên đồ án</th>
                <th scope="col">Mô tả/yêu cầu</th>
                <th scope="col">Số sinh viên</th>
                <th scope="col">Tài liệu tham khảo</th>
                <th scope="col">Giảng viên hướng dẫn</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Xây dựng plugin quản lý đăng ký đồ án trong Wordpress</td>
                <td>
                    <ul class="unorder-list">
                        <li>Tìm hiểu về Wordpress và ngôn ngữ PHP;</li>
                        <li>Phân tích và thiết kế cơ sở dữ liệu và chức năng;</li>
                        <li>Lập trình plugin bằng ngôn ngữ PHP;</li>
                        <li>Thử nghiệm và triển khai.</li>
                    </ul>
                </td>
                <td>3</td>
                <td>Liên hệ GVHD</td>
                <td>La Quốc Thắng</td>
            </tr>
        </tbody>
    </table>
    <div class="card">
        <div class="card-body">
            <form method="post" action="<?php echo get_current_url(); ?>" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-lg-3 col-md-3">
                        <div class="row">
                            <label class="col-sm-auto col-form-label">Loại đồ án:</label>
                            <div class="col-sm">
                                <select class="form-control" name="<?php echo UR_DO_AN; ?>_type" aria-label="Loại" title="Loại" id="cbxType">
                                    <?php
                                    echo '<option value="' . DO_AN_CO_SO . '">Đồ án cơ sở</option>';
                                    echo '<option value="' . DO_AN_CHUYEN_NGANH . '">Đồ án chuyên ngành</option>';
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3">
                        <div class="row">
                            <label class="col-sm-auto col-form-label">Lớp:</label>
                            <div class="col-sm">
                                <select class="form-control" name="<?php echo UR_DO_AN; ?>_class" aria-label="Lớp" title="Lớp" id="cbxClass">
                                    <option value="all" selected>Tất cả</option>
                                    <?php
                                    foreach ($list_classes as $item) {
                                        echo '<option value="' . $item . '">' . $item . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3">
                        <div class="row">
                            <label class="col-sm-auto col-form-label">Năm học</label>
                            <div class="col-sm">
                                <select class="form-control" name="<?php echo UR_DO_AN; ?>_schoolyear" aria-label="Năm học" title="Năm học" id="cbxSchoolyear">
                                    <?php
                                    $year = date('Y');
                                    for ($i = 0; $i < 10; $i++) {
                                        $value = $year - $i . '-' . $year - $i + 1;
                                        echo '<option value="' . $value . '">' . $value . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3">
                        <div class="row">
                            <label class="col-sm-auto col-form-label">Học kỳ</label>
                            <div class="col-sm">
                                <select class="form-control" name="<?php echo UR_DO_AN; ?>_semester" aria-label="Học kỳ" title="Học kỳ" id="cbxSemester">
                                    <option value="HK1">HK1</option>
                                    <option value="HK2">HK2</option>
                                    <option value="HK3">HK3</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label for="formFile" class="col-sm-auto col-form-label">Đường dẫn đến tập tin</label>
                    <div class="col">
                        <input class="form-control" type="file" id="formFile">
                    </div>
                </div>
                <div class="text-center mt-4">
                    <input type="submit" name="submit" value="Nhập dữ liệu" class="btn btn-primary">
                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">

</script>
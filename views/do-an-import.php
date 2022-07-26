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
require_once UR_PLUGIN_MODELS_DIR . '/DoAn.php';
require_once UR_PLUGIN_INCLUDES_DIR . '/utils.php';

date_default_timezone_set('Asia/Ho_Chi_Minh');

$list_classes = ur_Info::get_all_classes();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    // If file is empty or it has an error
    if (empty($_FILES) || $_FILES['file_to_upload']['size'] == 0 || $_FILES['file_to_upload']['error'] != 0 || $_FILES['file_to_upload']['size'] > 1048) {
        http_response_code(400); // Bad request
        exit();
    }
    $type = isset($_POST[UR_DO_AN . '_type']) ? $_POST[UR_DO_AN . '_type'] : '';
    $class = isset($_POST[UR_DO_AN . '_class']) ? $_POST[UR_DO_AN . '_class'] : '';
    $schoolyear = isset($_POST[UR_DO_AN . '_schoolyear']) ? $_POST[UR_DO_AN . '_schoolyear'] : '';
    $semester = isset($_POST[UR_DO_AN . '_semester']) ? $_POST[UR_DO_AN . '_semester'] : '';
    $start_date = is_not_null($_POST[UR_DO_AN . '_start_date']) ? $_POST[UR_DO_AN . '_start_date'] : null;
    $end_date = is_not_null($_POST[UR_DO_AN . '_end_date']) ? $_POST[UR_DO_AN . '_end_date'] : null;

    if (is_one_null_or_whitespace($type, $class, $schoolyear, $semester)) {
        http_response_code(400); // Bad request
        exit();
    } else {
        $file_name = $_FILES['file_to_upload']['tmp_name'];
        $csv = read_csv($file_name);
        $errors = array();

        array_shift($csv); // remove first item (title)
        foreach ($csv as $index => $row) {
            if (is_one_null_or_whitespace($row[0], $row[1], $row[2], $row[3], $row[4]))
                array_push($errors, $index + 1);
            else {
                $do_an = new ur_DoAn(null, null);
                $do_an->arr_to_obj($row, $type, $class, $schoolyear, $semester, $start_date, $end_date);
                $result = $do_an->insert_do_an();
                if (!$result)
                    array_push($errors, $index + 1);
            }
        }

        if (count($errors) > 0)
            echo '<script>alert("Lỗi xảy ra ở các dòng: ' . implode(', ', $errors) . '. Đã bỏ qua những dòng này.");</script>';

        // print("<pre>" . print_r($csv, true) ."</pre>");
        // exit();
    }
}

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
            <p><i>Tập tin đồ án phải tuân thủ thứ tự các cột, bao gồm cả tên cột và các dòng dữ liệu, <u>không có dòng hay cột trống</u>.</i></p>
        </li>
    </ul>
    <h6>Ví dụ về mẫu thông tin danh sách đồ án</h6>
    <table class="table table-bordered">
        <thead>
            <tr class="align-top">
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
            <form id="form-input-file" method="post" action="<?php echo get_current_url(); ?>" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="row">
                            <label class="col-sm-auto col-form-label">Loại đồ án:</label>
                            <div class="col-sm">
                                <select class="form-control" name="<?php echo UR_DO_AN; ?>_type" aria-label="Loại" title="Loại" id="cbxType" required>
                                    <?php
                                    echo '<option value="' . DO_AN_CO_SO . '">Đồ án cơ sở</option>';
                                    echo '<option value="' . DO_AN_CHUYEN_NGANH . '">Đồ án chuyên ngành</option>';
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="row">
                            <label class="col-sm-auto col-form-label">Lớp:
                                <p><i>(Nhấn giữ Ctrl để chọn nhiều lớp)</i></p>
                            </label>
                            <div class="col-sm">
                                <select class="form-control" name="<?php echo UR_DO_AN; ?>_class[]" multiple aria-label="Lớp" title="Lớp" id="cbxClass" required>
                                    <?php
                                    foreach ($list_classes as $item) {
                                        echo '<option value="' . $item . '">' . $item . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="row">
                            <label class="col-sm-auto col-form-label">Năm học</label>
                            <div class="col-sm">
                                <select class="form-control" name="<?php echo UR_DO_AN; ?>_schoolyear" aria-label="Năm học" title="Năm học" id="cbxSchoolyear" required>
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
                </div>
                <div class="row mt-2 mb-2">
                    <div class="col-auto">
                        <div class="row">
                            <label class="col-sm-auto col-form-label">Học kỳ</label>
                            <div class="col-sm">
                                <select class="form-control" name="<?php echo UR_DO_AN; ?>_semester" aria-label="Học kỳ" title="Học kỳ" id="cbxSemester" required>
                                    <option value="HK1">HK1</option>
                                    <option value="HK2">HK2</option>
                                    <option value="HK3">HK3</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-auto">
                        <div class="row">
                            <label class="col-auto col-form-label">Ngày bắt đầu đăng ký</label>
                            <div class="col-auto">
                                <input class="form-control" name="<?php echo UR_DO_AN; ?>_start_date" type="datetime-local" aria-label="Ngày bắt đầu" title="Ngày bắt đầu">
                            </div>
                        </div>
                    </div>
                    <div class="col-auto">
                        <div class="row">
                            <label class="col-auto col-form-label">Ngày kết thúc đăng ký</label>
                            <div class="col-auto">
                                <input class="form-control" name="<?php echo UR_DO_AN; ?>_end_date" type="datetime-local" aria-label="Ngày kết thúc" title="Ngày kết thúc">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <p><strong>Lưu ý: </strong><i>Chỉ nhập đồ án liên quan đến các thông tin ở trên, riêng các đồ án cũ thì bỏ qua thời gian đăng ký.</i></p>
                </div>
                <div class="row">
                    <div class="input-file">
                        <input type="file" id="formFile" name="file_to_upload" required>
                        <div>
                            <p id="input-file-name">Kéo thả tập tin vào đây</p>
                            <p><small>Kích thước tối đa: 1MB</small></p>
                        </div>
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
    jQuery(document).ready(function($) {
        function reset() {
            $('#formFile').val('');
            $('#input-file-name').text('Kéo thả tập tin vào đây');
        }

        $('#formFile').change(function(e) {
            var acceptedExts = ['csv', 'txt'];

            if (e.target.files.length != 0) {
                if ($.inArray($(this).val().split('.').pop().toLowerCase(), acceptedExts) == -1) {
                    alert("Chỉ chấp nhận các tập tin có phần mở rộng là .csv hoặc .txt!");
                    reset();
                } else if (e.target.files[0].size > 1024000) {
                    alert('Tập tin có kích thước lớn hơn 1MB!');
                    reset();
                } else {
                    let filename = e.target.files[0].name;
                    $('#input-file-name').text(filename);
                }
            }
        });

        $('#form-input-file').submit(function(e) {
            let files = $('#formFile').get(0).files;
            // Only one file should upload and its size is less than 2048KB
            if (files.length !== 0) {

            } else {
                alert('Bạn chưa chọn tập tin nào!');
                e.preventDefault();
            }
        });
    });
</script>
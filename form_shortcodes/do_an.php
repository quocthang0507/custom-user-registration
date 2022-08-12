<?php

/**
 * User view: Form đăng ký đồ án
 * 
 * @version  1.0.0
 * @package CustomUserRegistration
 */

require_once UR_PLUGIN_MODELS_DIR . '/Constants.php';
require_once UR_PLUGIN_MODELS_DIR . '/DoAn.php';
require_once UR_PLUGIN_MODELS_DIR . '/Info.php';
require_once UR_PLUGIN_INCLUDES_DIR . '/utils.php';

add_shortcode('ur_form_do_an', 'register_an_shortcode');

function registration_form(string $type = DO_AN_CO_SO)
{
    $user = wp_get_current_user();
    $list_do_an = ur_DoAn::get_all_do_an_co_so();
    $list_lop = ur_Info::get_all_classes();

?>
    <style>
        div {
            margin-bottom: 2px;
        }

        input {
            margin-bottom: 4px;
        }

        .grid-container {
            display: grid;
            grid-template-columns: auto auto;
            grid-gap: 10px;
        }
    </style>
    <form action="' . $_SERVER['REQUEST_URI'] . '" method="POST">
        <div class="grid-container">
            <div>
                <label for="last_name">Họ và tên đệm</label>
            </div>
            <div>
                <input type="text" name="last_name" value="<?php echo $user->user_lastname; ?>" readonly disabled>
            </div>
            <div>
                <label for="first_name">Tên</label>
            </div>
            <div>
                <input type="text" name="first_name" value="<?php echo $user->user_firstname; ?>" readonly disabled>
            </div>
            <div>
                <label for="student_id">Tên đăng nhập</label>
            </div>
            <div>
                <input type="text" name="student_id" value="<?php echo $user->user_login; ?>" readonly disabled>
            </div>
            <div>
                <label for="student_id">Mã số sinh viên</label>
            </div>
            <div>
                <input type="text" name="student_id" value="<?php echo $user->user_registration_student_id; ?>" readonly disabled>
            </div>
            <div>
                <label for="student_id">Địa chỉ email</label>
            </div>
            <div>
                <input type="email" name="email" value="<?php echo $user->user_email; ?>" readonly disabled>
            </div>
            <div>
                <label for="student_class">Lớp</label>
            </div>
            <div>
                <select name="student_class">
                    <?php
                    foreach ($list_lop as $lop) {
                        echo '<option value=' . $lop . '>' . $lop . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div>
                <label for="type">Loại đồ án</label>
            </div>
            <div>
                <select name="type" id="cbxType">
                    <option value="<?php echo DO_AN_CO_SO; ?>" <?php echo $type == DO_AN_CO_SO ? 'selected' : ''; ?>>Đồ án cơ sở</option>
                    <option value="<?php echo DO_AN_CHUYEN_NGANH; ?>" <?php echo $type == DO_AN_CHUYEN_NGANH ? 'selected' : ''; ?>>Đồ án chuyên ngành</option>
                </select>
            </div>
        </div>
        <input type="submit" name="submit" value="Đăng ký ngay" />
    </form>
    <section>
        <h3>DANH SÁCH ĐỀ TÀI CÓ THỂ ĐĂNG KÝ</h3>
        <table class="table">
            <tr>
                <th>Tên đề tài</th>
                <th>Mô tả/yêu cầu đề tài</th>
                <th>Giảng viên hướng dẫn</th>
                <th>Số sinh viên tối đa</th>
                <th>Tài liệu tham khảo</th>
                <th>Trạng thái đăng ký</th>
            </tr>
            <?php
            foreach ($list_do_an as $do_an) {
                echo '<td>' . $do_an->post_title . '</td>';
                echo '<td>' . $do_an->description . '</td>';
                echo '<td>' . $do_an->instructor . '</td>';
                echo '<td>' . $do_an->max_students . '</td>';
                echo '<td>' . $do_an->references . '</td>';
                echo '<td>' . 0 . '</td>';
            }
            ?>
        </table>
    </section>

    <script type="text/javascript">
        jQuery(document).ready(function($) {
            $('#cbxType').on('change', function() {
                let currentUrl = document.location.href;
                let url = new URL(currentUrl);
                url.searchParams.set('type', this.value);
                document.location = url.href;
            });
        });
    </script>
<?php
}

function custom_registration_function()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
        return;
    } else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $current_url = get_current_url();
        $type = parse_url_param($current_url, 'type');
        registration_form($type);
    }
}

function register_an_shortcode()
{
    ob_start();
    if (is_user_logged_in()) {
        custom_registration_function();
    } else {
        echo 'Bạn phải đăng nhập để thực hiện chức năng này!';
    }
    return ob_get_clean();
}

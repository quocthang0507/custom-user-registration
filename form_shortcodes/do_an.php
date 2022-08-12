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
    $list_lop = ur_Info::get_all_classes();
    $list_do_an = ur_DoAn::get_list_do_an_avaiable($type);

?>
    <style>
        .grid-container {
            display: grid;
            grid-template-columns: auto auto;
        }
    </style>
    <form action="' . $_SERVER['REQUEST_URI'] . '" method="POST">
        <div class="grid-container">
            <div>
                <p>Họ và tên đệm</p>
            </div>
            <div>
                <p><?php echo $user->user_lastname; ?></p>
            </div>
            <div>
                <p>Tên</p>
            </div>
            <div>
                <p><?php echo $user->user_firstname; ?></p>
            </div>
            <div>
                <p>Tên đăng nhập</p>
            </div>
            <div>
                <p><?php echo $user->user_login; ?></p>
            </div>
            <div>
                <p>Mã số sinh viên</p>
            </div>
            <div>
                <p><?php echo $user->user_registration_student_id; ?></p>
            </div>
            <div>
                <p>Địa chỉ email</p>
            </div>
            <div>
                <p><?php echo $user->user_email; ?></p>
            </div>
            <div>
                <p>Lớp</p>
            </div>
            <div>
                <p><?php echo $user->user_registration_class; ?></p>
            </div>
            <div>
                <p for="type">Loại đồ án</p>
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

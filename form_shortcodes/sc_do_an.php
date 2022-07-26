<?php

/**
 * User view: Form đăng ký đồ án
 * 
 * @version  1.0.0
 * @package CustomUserRegistration
 */

require_once UR_PLUGIN_MODELS_DIR . '/Constants.php';
require_once UR_PLUGIN_MODELS_DIR . '/DoAn.php';
require_once UR_PLUGIN_MODELS_DIR . '/DangKy.php';
require_once UR_PLUGIN_MODELS_DIR . '/Info.php';
require_once UR_PLUGIN_INCLUDES_DIR . '/utils.php';

add_shortcode('ur_form_do_an', 'register_an_shortcode');

function custom_registration_function()
{
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $current_url = get_current_url();
        $type = parse_url_param($current_url, 'type');
        registration_form($type ? $type : DO_AN_CO_SO);
    }
}

function registration_form(string $type = DO_AN_CO_SO)
{
    $user = wp_get_current_user();
    $list_lop = ur_Info::get_all_classes();
    $list_do_an = ur_DoAn::get_list_do_an_available($type);

?>
    <style>
        .grid-container {
            display: grid;
            grid-template-columns: auto auto;
        }

        table,
        th,
        td {
            border: solid 1px black;
            text-align: center;
            border-collapse: collapse;
        }

        .btn-register {
            background-color: #4CAF50;
            text-shadow: none;
        }

        .btn-unregister {
            background-color: #f44336;
            text-shadow: none;
        }
    </style>
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
            <select name="type" onchange="changeType(this);">
                <option value="<?php echo DO_AN_CO_SO; ?>" <?php echo $type == DO_AN_CO_SO ? 'selected' : ''; ?>>Đồ án cơ sở</option>
                <option value="<?php echo DO_AN_CHUYEN_NGANH; ?>" <?php echo $type == DO_AN_CHUYEN_NGANH ? 'selected' : ''; ?>>Đồ án chuyên ngành</option>
            </select>
        </div>
    </div>
    <div>
        <h3>DANH SÁCH ĐỀ TÀI CÓ THỂ ĐĂNG KÝ</h3>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">STT</th>
                    <th scope="col">Tên đề tài</th>
                    <th scope="col">Mô tả/yêu cầu đề tài</th>
                    <th scope="col">Giảng viên hướng dẫn</th>
                    <th scope="col">Số sinh viên đã đăng ký</th>
                    <th scope="col">Tài liệu tham khảo</th>
                    <th scope="col">Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($list_do_an as $index => $do_an) {
                    echo '<tr>';
                    echo '<th scope="row">' . $index + 1 . '</th>';
                    echo '<td>' . $do_an->post_title . '</td>';
                    echo '<td>' . $do_an->description . '</td>';
                    echo '<td>' . $do_an->instructor . '</td>';
                    echo '<td>' . $do_an->get_count_registration() . '/' . $do_an->max_students . '</td>';
                    echo '<td>' . $do_an->references . '</td>';
                    echo '<td>' .
                        '<div>';
                    if (ur_DangKy::is_user_already_registered($user->ID, $do_an->ID))
                        echo '<button type="button" class="btn-unregister" onclick="action_post(' . $do_an->ID . ', \'unregister\');">Hủy đăng ký</button>';
                    else
                        echo '<button type="button" class="btn-register" onclick="action_post(' . $do_an->ID . ', \'register\');">Đăng ký ngay</button>';
                    echo '</div>' .
                        '</td>';
                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>
    </div>

    <script type="text/javascript">
        function changeType(obj) {
            let currentUrl = document.location.href;
            let url = new URL(currentUrl);
            url.searchParams.set('type', obj.value);
            document.location = url.href;
        }

        function action_post(post_id, action) {
            jQuery(document).ready(function($) {
                let _nonce = "<?php echo wp_create_nonce('wp_rest'); ?>";

                $.ajax({
                    url: '<?php echo get_site_url() . '/wp-json/api/v1/registration'; ?>',
                    type: 'POST',
                    data: {
                        action: action,
                        post_id: post_id
                    },
                    beforeSend: function(xhr) {
                        xhr.setRequestHeader('X-WP-Nonce', _nonce);
                    },
                    success: function(response) {
                        location.reload();
                    },
                    error: function(xhr) {
                        alert('Đã có lỗi xảy ra!');
                        console.error(xhr);
                    }
                });
            });
        }
    </script>
<?php
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

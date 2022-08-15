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
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = parse_input_ajax();
        if (isset($data['action']) && isset($data['post_id'])) {
            $user = wp_get_current_user();
            $action = $data['action'];
            $post_id = $data['post_id'];

            if ($action == 'register') {
                ur_DangKy::register($user->ID, $post_id);
            } else if ($action == 'unregister') {
                ur_DangKy::unregister($user->ID, $post_id);
            }
            http_response_code(200);
            exit(0);
        }
    } else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
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
            <tr>
                <th>Tên đề tài</th>
                <th>Mô tả/yêu cầu đề tài</th>
                <th>Giảng viên hướng dẫn</th>
                <th>Số sinh viên đã đăng ký</th>
                <th>Tài liệu tham khảo</th>
                <th>Hành động</th>
            </tr>
            <tr>
                <?php
                foreach ($list_do_an as $do_an) {
                    echo '<td>' . $do_an->post_title . '</td>';
                    echo '<td>' . $do_an->description . '</td>';
                    echo '<td>' . $do_an->instructor . '</td>';
                    echo '<td>' . $do_an->get_count_registration() . '/' . $do_an->max_students . '</td>';
                    echo '<td>' . $do_an->references . '</td>';
                    echo '<td>' .
                        '<div>' .
                        '<button type="button" class="btn-register" onclick="action_post(' . $do_an->ID . ', \'register\');">Đăng ký ngay</button>' .
                        '<button type="button" class="btn-unregister" onclick="action_post(' . $do_an->ID . ', \'unregister\');">Hủy đăng ký</button>' .
                        '</div>' .
                        '</td>';
                }
                ?>
            </tr>
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
                $.ajax({
                    url: document.location.href,
                    type: 'POST',
                    data: {
                        action: action,
                        post_id: post_id
                    },
                    success: function(response) {
                        location.reload();
                    },
                    error: function(error) {
                        console.error(error);
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

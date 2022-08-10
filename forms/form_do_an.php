<?php

/**
 * User view: Form đăng ký đồ án
 * 
 * @version  1.0.0
 * @package CustomUserRegistration
 */

require_once UR_PLUGIN_MODELS_DIR . '/DoAn.php';

add_shortcode('ur_form_do_an', 'custom_registration_form_do_an_shortcode');

function registration_form()
{
    $user = wp_get_current_user();
    $list_do_an = ur_DoAn::get_all_do_an_co_so();

?>
    <style>
        div {
            margin-bottom: 2px;
        }

        input {
            margin-bottom: 4px;
        }
    </style>
    <form action="' . $_SERVER['REQUEST_URI'] . '" method="POST">
        <div>
            <label for="last_name">Họ và tên đệm</label>
            <input type="text" name="last_name" value="<?php echo $user->user_lastname; ?>" readonly disabled>
        </div>
        <div>
            <label for="first_name">Tên</label>
            <input type="text" name="first_name" value="<?php echo $user->user_firstname; ?>" readonly disabled>
        </div>
        <div>
            <label for="student_id">Tên đăng nhập</label>
            <input type="text" name="student_id" value="<?php echo $user->user_login; ?>" readonly disabled>
        </div>
        <div>
            <label for="student_id">Mã số sinh viên</label>
            <input type="text" name="student_id" value="<?php echo $user->user_registration_student_id; ?>" readonly disabled>
        </div>
        <div>
            <label for="student_id">Địa chỉ email</label>
            <input type="email" name="email" value="<?php echo $user->user_email; ?>" readonly disabled>
        </div>
        <div>
            <label for="student_class">Lớp</label>
            <select name="student_class">
                <option value="CTK43-PM">CTK43-PM</option>
                <option value="CTK44A">CTK44A</option>
                <option value="CTK44B">CTK44B</option>
            </select>
        </div>
        <div>
            <label for="type">Loại đồ án</label>
            <select name="student_class">
                <option value="do-an-chuyen-nganh">Đồ án chuyên ngành</option>
                <option value="do-an-co-so">Đồ án cơ sở</option>
            </select>
        </div>
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
        <input type="submit" name="submit" value="Đăng ký ngay" />
    </form>
<?php
}

function custom_registration_function()
{
    if (isset($_POST['submit'])) {
        return;
    }
    registration_form();
}

function custom_registration_form_do_an_shortcode()
{
    ob_start();
    if (is_user_logged_in()) {
        custom_registration_function();
    } else {
        echo 'Bạn phải đăng nhập để thực hiện chức năng này!';
    }
    return ob_get_clean();
}

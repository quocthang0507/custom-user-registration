<?php

/**
 * Tạo Custom Post Type đăng ký đồ án
 * 
 * @version  1.0.0
 * @package CustomUserRegistration
 */

require_once UR_PLUGIN_MODELS_DIR . '/Constants.php';
require_once UR_PLUGIN_MODELS_DIR . '/DoAn.php';
require_once UR_PLUGIN_INCLUDES_DIR . '/utils.php';
require_once UR_PLUGIN_MODELS_DIR . '/Info.php';

function init_ur_do_an()
{
    add_action('init', GENERATE_DO_AN_POST_TYPE);
    add_action('add_meta_boxes_' . UR_DO_AN, DO_AN_METABOX);
    add_action('save_post_' . UR_DO_AN, 'ur_do_an_save');
}

function ur_do_an_metabox()
{
    add_meta_box('ur_do_an_info', 'Thông tin Đồ án', 'ur_do_an_output');
}

if (!function_exists(GENERATE_DO_AN_POST_TYPE)) {
    function generate_do_an_post_type()
    {
        $label = array(
            'name' => MENU_QUAN_LY_DO_AN, // Tên post type số nhiều
            'singular_name' => MENU_QUAN_LY_DO_AN, // Tên post type số ít
            'add_new' => __(MENU_ADD_DO_AN), // Hiện trên menu
            'add_new_item' => __(MENU_ADD_DO_AN), // Hiện trên tiêu đề trang
            'edit_item' => __(MENU_EDIT_DO_AN),
            'new_item' => __(MENU_ADD_DO_AN),
            'not_found' => __(MENU_NOT_FOUND_DO_AN),
            'not_found_in_trash' => __(MENU_NOT_FOUND_TRASH_DO_AN),
            'all_items' => __(MENU_ALL_DO_AN),
        );
        $args = array(
            'labels' => $label, // Gọi các label ở trên
            UR_DO_AN . 'description' => DESCRIPTION_DO_AN, // Mô tả
            'supports' => array( // Các tính năng được hỗ trợ trong post type
                'title',
                'author'
            ),
            'taxanomies' => array('categories', 'post_tag'), // Sử dụng taxanomy phân loại nội dung
            'register_meta_box_cb' => DO_AN_METABOX,
            'hierarchical' => false, // False thì post type này giống như Post, true thì giống như Page 
            'public' => true, // Kích hoạt post type
            'show_ui' => true, // Hiển thị
            'show_in_menu' => true, // Hiển thị trên Admin Menu (tay trái)
            'show_in_nav_menus' => true, // Hiển thị trong Appearance -> Menus
            'show_in_admin_bar' => true, // Hiển thị trên thanh Admin bar màu đen.
            'menu_position' => 5, // Thứ tự vị trí hiển thị trong menu (tay trái), giá trị từ 5 đến 100
            'menu_icon' => 'data:image/svg+xml;base64,PHN2ZyB2ZXJzaW9uPSIxLjEiIGlkPSJVcGxvYWRlZCB0byBzdmdyZXBvLmNvbSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgeD0iMHB4IiB5PSIwcHgiDQoJIHdpZHRoPSIzMnB4IiBoZWlnaHQ9IjMycHgiIHZpZXdCb3g9IjAgMCAzMiAzMiIgc3R5bGU9ImVuYWJsZS1iYWNrZ3JvdW5kOm5ldyAwIDAgMzIgMzI7IiB4bWw6c3BhY2U9InByZXNlcnZlIj4NCjxzdHlsZSB0eXBlPSJ0ZXh0L2NzcyI+DQoJLnB1Y2hpcHVjaGlfZWVue2ZpbGw6IzExMTkxODt9DQo8L3N0eWxlPg0KPHBhdGggY2xhc3M9InB1Y2hpcHVjaGlfZWVuIiBkPSJNMjYsMUg2QzQuOSwxLDQsMS45LDQsM3YyNmMwLDEuMSwwLjksMiwyLDJoMjBjMS4xLDAsMi0wLjksMi0yVjNDMjgsMS45LDI3LjEsMSwyNiwxeiBNMTAsMjMNCgljLTAuNTUyLDAtMS0wLjQ0OC0xLTFjMC0wLjU1MiwwLjQ0OC0xLDEtMXMxLDAuNDQ4LDEsMUMxMSwyMi41NTIsMTAuNTUyLDIzLDEwLDIzeiBNMTAsMTljLTAuNTUyLDAtMS0wLjQ0OC0xLTENCgljMC0wLjU1MiwwLjQ0OC0xLDEtMXMxLDAuNDQ4LDEsMUMxMSwxOC41NTIsMTAuNTUyLDE5LDEwLDE5eiBNMTAsMTVjLTAuNTUyLDAtMS0wLjQ0OC0xLTFjMC0wLjU1MiwwLjQ0OC0xLDEtMXMxLDAuNDQ4LDEsMQ0KCUMxMSwxNC41NTIsMTAuNTUyLDE1LDEwLDE1eiBNMTAsMTFjLTAuNTUyLDAtMS0wLjQ0OC0xLTFjMC0wLjU1MiwwLjQ0OC0xLDEtMXMxLDAuNDQ4LDEsMUMxMSwxMC41NTIsMTAuNTUyLDExLDEwLDExeiBNMjIsMjNoLTgNCgljLTAuNTUyLDAtMS0wLjQ0OC0xLTFzMC40NDgtMSwxLTFoOGMwLjU1MiwwLDEsMC40NDgsMSwxUzIyLjU1MiwyMywyMiwyM3ogTTIyLDE5aC04Yy0wLjU1MiwwLTEtMC40NDgtMS0xczAuNDQ4LTEsMS0xaDgNCgljMC41NTIsMCwxLDAuNDQ4LDEsMVMyMi41NTIsMTksMjIsMTl6IE0yMiwxNWgtOGMtMC41NTIsMC0xLTAuNDQ4LTEtMXMwLjQ0OC0xLDEtMWg4YzAuNTUyLDAsMSwwLjQ0OCwxLDFTMjIuNTUyLDE1LDIyLDE1eiBNMjIsMTENCgloLThjLTAuNTUyLDAtMS0wLjQ0OC0xLTFzMC40NDgtMSwxLTFoOGMwLjU1MiwwLDEsMC40NDgsMSwxUzIyLjU1MiwxMSwyMiwxMXoiLz4NCjwvc3ZnPg==', // Đường dẫn tới icon sẽ hiển thị
            'can_export' => true, // Có thể export nội dung bằng Tools -> Export
            'has_archive' => true, // Cho phép lưu trữ (month, date, year)
            'exclude_from_search' => false, // Loại bỏ khỏi kết quả tìm kiếm
            'publicly_queryable' => true, // Hiển thị các tham số trong query, phải đặt true
            'capability_type' => 'post',
            'publicly_queryable' => false, // Không hiện nút View trong danh sách
            'rewrite' => array('slug' => 'do_an'), //
        );
        register_post_type(UR_DO_AN, $args);
    }
}

function ur_do_an_save($post_id)
{
    if (
        isset($_POST['post_title']) &&
        isset($_POST[UR_DO_AN . '_description']) &&
        isset($_POST[UR_DO_AN . '_instructor']) &&
        isset($_POST[UR_DO_AN . '_max_students']) &&
        isset($_POST[UR_DO_AN . '_references']) &&
        isset($_POST[UR_DO_AN . '_start_date']) &&
        isset($_POST[UR_DO_AN . '_end_date']) &&
        isset($_POST[UR_DO_AN . '_schoolyear']) &&
        isset($_POST[UR_DO_AN . '_semester']) &&
        isset($_POST[UR_DO_AN . '_class']) &&
        isset($_POST[UR_DO_AN . '_type'])
    ) {
        $data = new ur_DoAn($_POST, null);
        return ur_DoAn::update_do_an($post_id, null, $data);
    }
    return false;
}

function ur_do_an_output()
{
    $id = get_the_ID();
    $description = get_post_meta($id, UR_DO_AN . '_description', true);
    $instructor = get_post_meta($id, UR_DO_AN . '_instructor', true);
    $max_students = get_post_meta($id, UR_DO_AN . '_max_students', true);
    $references = get_post_meta($id, UR_DO_AN . '_references', true);
    $start_date = get_post_meta($id, UR_DO_AN . '_start_date', true);
    $end_date = get_post_meta($id, UR_DO_AN . '_end_date', true);
    $schoolyear = get_post_meta($id, UR_DO_AN . '_schoolyear', true);
    $semester = get_post_meta($id, UR_DO_AN . '_semester', true);
    $temp = get_post_meta($id, UR_DO_AN . '_class', true);
    $classes = $temp != null ? explode(', ', $temp) : array();
    $type = get_post_meta($id, UR_DO_AN . '_type', true);

    $list_instructors = ur_Info::get_all_instructors();
    $list_classes = ur_Info::get_all_classes();

?>
    <!--Metabox hiển thị khi phía dưới ở trang Thêm mới Đồ án-->
    <form>
        <div class="ur_do_an_detail">
            <div class="mb-1">
                <label class="form-label">Loại đồ án</label>
                <select class="form-control" name="<?php echo UR_DO_AN; ?>_type" aria-label="Loại đồ án" title="Loại đồ án">
                    <option value="<?php echo DO_AN_CO_SO; ?>" <?php echo $type == DO_AN_CO_SO ? 'selected' : ''; ?>>Đồ án cơ sở</option>
                    <option value="<?php echo DO_AN_CHUYEN_NGANH; ?>" <?php echo $type == DO_AN_CHUYEN_NGANH ? 'selected' : ''; ?>>Đồ án chuyên ngành</option>
                </select>
            </div>
            <div class="mb-1">
                <label class="form-label">Mô tả</label>
                <textarea class="form-control" name="<?php echo UR_DO_AN; ?>_description" rows="5" aria-label="Mô tả" title="Mô tả"><?php echo $description; ?></textarea>
            </div>
            <div class="row mb-1">
                <div class="col">
                    <label class="form-label">Giảng viên hướng dẫn</label>
                    <select class="form-control" name="<?php echo UR_DO_AN; ?>_instructor" aria-label="GVHD" title="GVHD">
                        <?php
                        foreach ($list_instructors as $item) {
                            if ($item == $instructor)
                                echo '<option value="' . $item . '" selected>' . $item . '</option>';
                            else
                                echo '<option value="' . $item . '">' . $item . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="col">
                    <label class="form-label">Số sinh viên tối đa</label>
                    <input class="form-control" type="number" name="<?php echo UR_DO_AN; ?>_max_students" min="0" max="10" value="<?php echo $max_students; ?>" aria-label="Số SV tối đa" title="Số SV tối đa" required>
                </div>
            </div>
            <div class="mb-1">
                <label class="form-label">Tài liệu tham khảo</label>
                <textarea class="form-control" name="<?php echo UR_DO_AN; ?>_references" rows="5" aria-label="Tài liệu tham khảo" title="Tài liệu tham khảo"><?php echo $references; ?></textarea>
            </div>
            <div class="row mb-1">
                <div class="col">
                    <label class="form-label">Ngày bắt đầu đăng ký</label>
                    <input class="form-control" type="datetime-local" name="<?php echo UR_DO_AN; ?>_start_date" value="<?php echo $start_date; ?>" aria-label="Ngày bắt đầu" title="Ngày bắt đầu" required>
                </div>
                <div class="col">
                    <label class="form-label">Ngày kết thúc đăng ký</label>
                    <input class="form-control" type="datetime-local" name="<?php echo UR_DO_AN; ?>_end_date" value="<?php echo $end_date; ?>" aria-label="Ngày kết thúc" title="Ngày kết thúc" required>
                </div>
            </div>
            <div class="row mb-1">
                <div class="col">
                    <label class="form-label">Năm học</label>
                    <select class="form-control" name="<?php echo UR_DO_AN; ?>_schoolyear" aria-label="Năm học" title="Năm học">
                        <?php
                        $year = date('Y');
                        for ($i = 0; $i < 10; $i++) {
                            $value = $year - $i . '-' . $year - $i + 1;
                            if ($schoolyear == $value)
                                echo '<option value="' . $value . '" selected>' . $value . '</option>';
                            else
                                echo '<option value="' . $value . '">' . $value . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="col">
                    <label class="form-label">Học kỳ</label>
                    <select class="form-control" name="<?php echo UR_DO_AN; ?>_semester" aria-label="Học kỳ" title="Học kỳ">
                        <option value="HK1" <?php echo $semester == 'HK1' ? 'selected' : '' ?>>HK1</option>
                        <option value="HK2" <?php echo $semester == 'HK2' ? 'selected' : '' ?>>HK2</option>
                        <option value="HK3" <?php echo $semester == 'HK3' ? 'selected' : '' ?>>HK3</option>
                    </select>
                </div>
                <div class="col">
                    <label class="form-label">Lớp
                        <i>(Nhấn giữ Ctrl để chọn nhiều lớp)</i>
                    </label>
                    <select class="form-control" name="<?php echo UR_DO_AN; ?>_class[]" multiple aria-label="Lớp" title="Lớp">
                        <?php
                        foreach ($list_classes as $item) {
                            if (in_array($item, $classes))
                                echo '<option value="' . $item . '" selected>' . $item . '</option>';
                            else
                                echo '<option value="' . $item . '">' . $item . '</option>';
                        }
                        ?>
                    </select>
                </div>
            </div>
        </div>
    </form>
<?php
}

<?php

require_once UR_PLUGIN_MODELS_DIR . '/Constants.php';
require_once UR_PLUGIN_MODELS_DIR . '/DoAn.php';
require_once UR_PLUGIN_INCLUDES . './utils.php';

function init_ur_do_an()
{
    add_action('init', GENERATE_DO_AN_POST_TYPE);
    add_action('add_meta_boxes_ur_do_an', DO_AN_METABOX);
    add_action('save_post_ur_do_an', 'ur_do_an_update');
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
            'description' => DESCRIPTION_DO_AN, // Mô tả
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
            'menu_position' => 5, // Thứ tự vị trí hiển thị trong menu (tay trái)
            'menu_icon' => 'data:image/svg+xml;base64,PHN2ZyB2ZXJzaW9uPSIxLjEiIGlkPSJVcGxvYWRlZCB0byBzdmdyZXBvLmNvbSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgeD0iMHB4IiB5PSIwcHgiDQoJIHdpZHRoPSIzMnB4IiBoZWlnaHQ9IjMycHgiIHZpZXdCb3g9IjAgMCAzMiAzMiIgc3R5bGU9ImVuYWJsZS1iYWNrZ3JvdW5kOm5ldyAwIDAgMzIgMzI7IiB4bWw6c3BhY2U9InByZXNlcnZlIj4NCjxzdHlsZSB0eXBlPSJ0ZXh0L2NzcyI+DQoJLnB1Y2hpcHVjaGlfZWVue2ZpbGw6IzExMTkxODt9DQo8L3N0eWxlPg0KPHBhdGggY2xhc3M9InB1Y2hpcHVjaGlfZWVuIiBkPSJNMjYsMUg2QzQuOSwxLDQsMS45LDQsM3YyNmMwLDEuMSwwLjksMiwyLDJoMjBjMS4xLDAsMi0wLjksMi0yVjNDMjgsMS45LDI3LjEsMSwyNiwxeiBNMTAsMjMNCgljLTAuNTUyLDAtMS0wLjQ0OC0xLTFjMC0wLjU1MiwwLjQ0OC0xLDEtMXMxLDAuNDQ4LDEsMUMxMSwyMi41NTIsMTAuNTUyLDIzLDEwLDIzeiBNMTAsMTljLTAuNTUyLDAtMS0wLjQ0OC0xLTENCgljMC0wLjU1MiwwLjQ0OC0xLDEtMXMxLDAuNDQ4LDEsMUMxMSwxOC41NTIsMTAuNTUyLDE5LDEwLDE5eiBNMTAsMTVjLTAuNTUyLDAtMS0wLjQ0OC0xLTFjMC0wLjU1MiwwLjQ0OC0xLDEtMXMxLDAuNDQ4LDEsMQ0KCUMxMSwxNC41NTIsMTAuNTUyLDE1LDEwLDE1eiBNMTAsMTFjLTAuNTUyLDAtMS0wLjQ0OC0xLTFjMC0wLjU1MiwwLjQ0OC0xLDEtMXMxLDAuNDQ4LDEsMUMxMSwxMC41NTIsMTAuNTUyLDExLDEwLDExeiBNMjIsMjNoLTgNCgljLTAuNTUyLDAtMS0wLjQ0OC0xLTFzMC40NDgtMSwxLTFoOGMwLjU1MiwwLDEsMC40NDgsMSwxUzIyLjU1MiwyMywyMiwyM3ogTTIyLDE5aC04Yy0wLjU1MiwwLTEtMC40NDgtMS0xczAuNDQ4LTEsMS0xaDgNCgljMC41NTIsMCwxLDAuNDQ4LDEsMVMyMi41NTIsMTksMjIsMTl6IE0yMiwxNWgtOGMtMC41NTIsMC0xLTAuNDQ4LTEtMXMwLjQ0OC0xLDEtMWg4YzAuNTUyLDAsMSwwLjQ0OCwxLDFTMjIuNTUyLDE1LDIyLDE1eiBNMjIsMTENCgloLThjLTAuNTUyLDAtMS0wLjQ0OC0xLTFzMC40NDgtMSwxLTFoOGMwLjU1MiwwLDEsMC40NDgsMSwxUzIyLjU1MiwxMSwyMiwxMXoiLz4NCjwvc3ZnPg==', // Đường dẫn tới icon sẽ hiển thị
            'can_export' => true, // Có thể export nội dung bằng Tools -> Export
            'has_archive' => true, // Cho phép lưu trữ (month, date, year)
            'exclude_from_search' => false, // Loại bỏ khỏi kết quả tìm kiếm
            'publicly_queryable' => true, // Hiển thị các tham số trong query, phải đặt true
            'capability_type' => 'post',
            'rewrite' => array('slug' => 'do_an'), //
        );
        register_post_type('ur_do_an', $args);
    }
}

function ur_do_an_update($post_id)
{
    if (
        isset($_POST['post_title']) &&
        isset($_POST['description']) &&
        isset($_POST['instructor']) &&
        isset($_POST['max_students']) &&
        isset($_POST['references']) &&
        isset($_POST['start_date']) &&
        isset($_POST['end_date']) &&
        isset($_POST['schoolyear']) &&
        isset($_POST['semester']) &&
        isset($_POST['class'])
    ) {
        $data = new ur_DoAn($_POST);
        return ur_DoAn::update_do_an($post_id, '', $data);
    }
    return false;
}

function ur_do_an_output()
{
    $id = get_the_ID();
    $description = get_post_meta($id, "description", true);
    $instructor = get_post_meta($id, "instructor", true);
    $max_students = get_post_meta($id, "max_students", true);
    $references = get_post_meta($id, "references", true);
    $start_date = get_post_meta($id, "start_date", true);
    $end_date = get_post_meta($id, "end_date", true);
    $schoolyear = get_post_meta($id, "schoolyear", true);
    $semester = get_post_meta($id, "semester", true);
    $class = get_post_meta($id, "class", true);

?>
    <!--Metabox hiển thị khi phía dưới ở trang Thêm mới Đồ án-->
    <form>
        <div class="ur_do_an_detail">
            <div class="form-group">
                <label>Mô tả</label>
                <textarea class="form-control" name="description" rows="4" aria-label="Mô tả" title="Mô tả"><?php echo $description; ?></textarea>
            </div>
            <div class="form-group">
                <label>Giảng viên hướng dẫn</label>
                <input class="form-control" type="text" name="instructor" value="<?php echo $instructor; ?>" aria-label="GVHD" title="GVHD">
            </div>
            <div class="form-group">
                <label>Số sinh viên tối đa</label>
                <input class="form-control" type="number" name="max_students" min="0" max="10" value="<?php echo $max_students; ?>" aria-label="Số SV tối đa" title="Số SV tối đađa">
            </div>
            <div class="form-group">
                <label>Tài liệu tham khảo</label>
                <textarea class="form-control" name="references" rows="4" aria-label="Tài liệu tham khảo" title="Tài liệu tham khảo"><?php echo $references; ?></textarea>
            </div>
            <div class="form-group">
                <label>Ngày bắt đầu đăng ký</label>
                <input class="form-control" type="date" name="start_date" value="<?php echo dmy2ymd($start_date); ?>" aria-label="Ngày bắt đầu" title="Ngày bắt đầu">
            </div>
            <div class="form-group">
                <label>Ngày kết thúc đăng ký</label>
                <input class="form-control" type="date" name="end_date" value="<?php echo dmy2ymd($end_date); ?>" aria-label="Ngày kết thúc" title="Ngày kết thúc">
            </div>
            <div class="form-group">
                <label>Năm học</label>
                <select class="form-control" name="schoolyear" aria-label="Năm học" title="Năm học">
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
            <div class="form-group">
                <label>Học kỳ</label>
                <select class="form-control" name="semester" aria-label="Học kỳ" title="Học kỳ">
                    <option value="HK1" <?php echo $semester == 'HK1' ? 'selected' : '' ?>>HK1</option>
                    <option value="HK2" <?php echo $semester == 'HK2' ? 'selected' : '' ?>>HK2</option>
                    <option value="HK3" <?php echo $semester == 'HK3' ? 'selected' : '' ?>>HK3</option>
                </select>
            </div>
            <div class="form-group">
                <label>Lớp</label>
                <input class="form-control" type="text" name="class" value="<?php echo $class; ?>" aria-label="Lớp" title="Lớp">
            </div>
        </div>
    </form>
<?php
}

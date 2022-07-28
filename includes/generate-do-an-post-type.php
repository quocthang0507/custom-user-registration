<?php

require_once UR_PLUGIN_MODELS_DIR . 'Constants.php';
require_once UR_PLUGIN_MODELS_DIR . 'DoAn.php';

function init_ur_do_an()
{
    add_action('init', GENERATE_POST_TYPE);
}

if (!function_exists(GENERATE_POST_TYPE)) {
    function generate_do_an_post_type()
    {
        $label = array(
            'name' => MENU_QUAN_LY_DO_AN, // Tên post type số nhiều
            'singular_name' => MENU_QUAN_LY_DO_AN, // Tên post type số ít
            'add_new' => __(MENU_THEM_DO_AN),
        );
        $args = array(
            'labels' => $label, // Gọi các label ở trên
            'description' => DESCRIPTION_DO_AN, // Mô tả
            'supports' => array( // Các tính năng được hỗ trợ trong post type
                'title',
                'author'
            ),
            'taxanomies' => array('categories', 'post_tag'), // Sử dụng taxanomy phân loại nội dung
            'register_meta_box_cb' => DO_AN_META_BOX,
            'hierarchical' => false, // False thì post type này giống như Post, true thì giống như Page 
            'public' => true, // Kích hoạt post type
            'show_ui' => true, // Hiển thị
            'show_in_menu' => true, // Hiển thị trên Admin Menu (tay trái)
            'show_in_nav_menus' => true, // Hiển thị trong Appearance -> Menus
            'show_in_admin_bar' => true, // Hiển thị trên thanh Admin bar màu đen.
            'menu_position' => 5, // Thứ tự vị trí hiển thị trong menu (tay trái)
            'menu_icon' => 'data:image/png;base64,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', // Đường dẫn tới icon sẽ hiển thị
            'can_export' => true, // Có thể export nội dung bằng Tools -> Export
            'has_archive' => true, // Cho phép lưu trữ (month, date, year)
            'exclude_from_search' => false, // Loại bỏ khỏi kết quả tìm kiếm
            'publicly_queryable' => true, // Hiển thị các tham số trong query, phải đặt true
            'capability_type' => 'post',
            'rewrite' => array('slug' => 'do_an'), //
        );
        register_post_type('DoAn', $args);
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
    
}
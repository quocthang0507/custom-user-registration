<?php

require_once UR_PLUGIN_INCLUDES . '/utils.php';

// Enqueue scripts for all admin page
add_action('admin_enqueue_scripts', 'load_plugin_css');
add_action('manage_posts_custom_column', 'custom_columns');
add_action('admin_menu', 'add_sub_menus');

function load_plugin_css()
{
    wp_register_style('style', plugins_url('custom-user-registration/css/style.css'), __FILE__);
    wp_enqueue_style('style');
}

/**
 * Display data in new columns
 */
function custom_columns($column)
{
    global $post;
    switch ($column) {
        case 'instructor':
            $instructor = get_post_meta($post->ID, 'instructor', true);
            echo $instructor;
            break;
        case 'type':
            $type = get_post_meta($post->ID, 'type', true);
            if ($type == DO_AN_CO_SO)
                echo 'Đồ án cơ sở';
            else if ($type == DO_AN_CHUYEN_NGANH)
                echo 'Đồ án chuyên ngành';
            break;
        case 'start_date':
            $date = get_post_meta($post->ID, 'start_date', true);
            echo date_to_string($date);
            break;
        case 'end_date':
            $date = get_post_meta($post->ID, 'end_date', true);
            echo date_to_string($date);
            break;
        case 'status':
            echo '0';
            break;
    }
}

/**
 * Add sub-menus for this plugin
 */
function add_sub_menus()
{
    add_submenu_page(
        'edit.php?post_type=ur_do_an',
        'Danh sách đăng ký',
        'Danh sách đăng ký',
        'manage_options',
        'ur_do_an-result',
        'result_page',
    );
}

function result_page()
{
    do_action('ur_do_an_result_start');
    include UR_PLUGIN_VIEWS_DIR . '/do-an-result.php';
}

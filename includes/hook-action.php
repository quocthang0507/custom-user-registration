<?php

require_once UR_PLUGIN_MODELS_DIR . '/Constants.php';
require_once UR_PLUGIN_INCLUDES_DIR . '/utils.php';

// Enqueue scripts for all admin page
add_action('admin_enqueue_scripts', 'load_plugin_css_js');
// Customize columns in table
add_action('manage_posts_custom_column', 'custom_columns');
// Add sub menus in plugin menu
add_action('admin_menu', 'add_sub_menus');
// Add action to ur_do_an
add_action('admin_notices', 'show_admin_notices');
// Add scripts
add_action('admin_head-edit.php', 'add_custom_scripts');

function load_plugin_css_js()
{
    // css
    wp_register_style('style', plugins_url('custom-user-registration/css/style.css'), __FILE__);
    wp_enqueue_style('style');

    // js
    wp_enqueue_script('script', plugins_url('custom-user-registration/js/script.js'), __FILE__);
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

/**
 * Show a nocie after our custom bulk action is done
 */
function show_admin_notices()
{
    if (!empty($_REQUEST['changed-datetime'])) {
        $num_changes = (int)$_REQUEST['changed-datetime'];
        printf('<div id="message" class="updated notice is-dismissable"><p>' . __('Đã thay đổi thời gian %d đồ án.', 'txtdomain') . '</p></div>', $num_changes);
    }
}

function add_custom_scripts()
{
    global $current_screen;
    global $start_date, $end_date;
    switch ($current_screen->post_type) {
        case 'ur_do_an':
?>
            <script type="text/javascript">
                jQuery(document).ready(function($) {
                    let change_date_div = $(
                        '<div class="alignleft actions" style="display: none;" id="change_datetime">' +
                        '<label>Ngày bắt đầu:</label>' +
                        '<input class="form-control" type="datetime-local" name="start_date" aria-label="Ngày bắt đầu" title="Ngày bắt đầu" value="<?php echo $start_date; ?>">' +
                        '<label>Ngày bắt đầu:</label>' +
                        '<input class="form-control" type="datetime-local" name="end_date" aria-label="Ngày kết thúc" title="Ngày kết thúc" value="<?php echo $end_date; ?>">' +
                        '</div>'
                    );
                    change_date_div.insertAfter('#bulk-action-selector-top');
                });
            </script>
<?php
            break;
    }
}

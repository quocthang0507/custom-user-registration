<?php

/**
 * Hook actions
 * 
 * @version  1.0.0
 * @package CustomUserRegistration
 */

/**
 * Actions are the hooks that the WordPress core launches at specific points during execution, 
 * or when specific events occur. Plugins can specify that one or more of its PHP functions 
 * are executed at these points, using the Action API.
 */

require_once UR_PLUGIN_MODELS_DIR . '/Constants.php';
require_once UR_PLUGIN_INCLUDES_DIR . '/utils.php';
require_once UR_PLUGIN_INCLUDES_DIR . '/rest-api.php';
require_once UR_PLUGIN_MODELS_DIR . '/Info.php';
require_once UR_PLUGIN_MODELS_DIR . '/DoAn.php';

/**
 * add_action( string $hook_name, callable $callback, int $priority = 10, int $accepted_args = 1 
 * Adds a callback function to an action hook.
 * 
 * Parameters
 * $hook_name: (string) (Required) The name of the action to add the callback to.
 * $callback: (callable) (Required) The callback to be run when the action is called.
 * $priority: (int) (Optional) Used to specify the order in which the functions associated with a particular action are executed. 
 * Lower numbers correspond with earlier execution, and functions with the same priority are executed in the order in which they 
 * were added to the action.
 * Default value: 10
 * $accepted_args: (int) (Optional) The number of arguments the function accepts.
 * Default value: 1
 * 
 * Return
 * (true) Always returns true.
 */

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
// Create more fields in bulk edit
add_action('quick_edit_custom_box', 'change_quick_edit', 10, 3);
// Save quick edit fields
add_action('save_post', 'save_quick_edit');
// Custom endpoint Rest API
add_action('rest_api_init', 'register_api');
// Custom filter by meta data
add_action('load-edit.php', 'load_custom_filter_metadata');

function load_plugin_css_js()
{
    // css
    wp_register_style('bootstrap', plugins_url('custom-user-registration/css/bootstrap.min.css'), __FILE__);
    wp_enqueue_style('bootstrap');
    wp_register_style('style', plugins_url('custom-user-registration/css/style.css'), __FILE__);
    wp_enqueue_style('style');

    // js
    wp_enqueue_script('script', plugins_url('custom-user-registration/js/script.js'), __FILE__);
    wp_enqueue_script('bootstrap', plugins_url('custom-user-registration/js/bootstrap.min.js'), __FILE__);
}

/**
 * Display data in new columns
 */
function custom_columns($column)
{
    global $post;
    switch ($column) {
        case UR_DO_AN . '_instructor':
            // get_post_meta( int $post_id, string $key = '', bool $single = false )
            $instructor = get_post_meta($post->ID, UR_DO_AN . '_instructor', true);
            echo $instructor;
            break;
        case UR_DO_AN . '_type':
            $type = get_post_meta($post->ID, UR_DO_AN . '_type', true);
            if ($type == DO_AN_CO_SO)
                echo 'Đồ án cơ sở';
            else if ($type == DO_AN_CHUYEN_NGANH)
                echo 'Đồ án chuyên ngành';
            break;
        case UR_DO_AN . '_class':
            $class = get_post_meta($post->ID, UR_DO_AN . '_class', true);
            echo $class;
            break;
        case UR_DO_AN . '_start_date':
            $date = get_post_meta($post->ID, UR_DO_AN . '_start_date', true);
            echo date_to_string($date);
            break;
        case UR_DO_AN . '_end_date':
            $date = get_post_meta($post->ID, UR_DO_AN . '_end_date', true);
            echo date_to_string($date);
            break;
        case UR_DO_AN . '_status':
            echo ur_DangKy::get_count_registration_by_id($post->ID);
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
        IMPORT_DO_AN,
        IMPORT_DO_AN,
        'manage_options',
        'ur_do_an-import',
        'link_to_import_page',
    );
    add_submenu_page(
        'edit.php?post_type=ur_do_an',
        INFO_DO_AN,
        INFO_DO_AN,
        'manage_options',
        'ur_do_an-info',
        'link_to_info_page',
    );
    add_submenu_page(
        'edit.php?post_type=ur_do_an',
        REGISTERED_DO_AN,
        REGISTERED_DO_AN,
        'manage_options',
        'ur_do_an-result',
        'link_to_result_page',
    );
}

function link_to_import_page()
{
    do_action('ur_do_an_import_start');
    include UR_PLUGIN_VIEWS_DIR . '/do-an-import.php';
}

function link_to_info_page()
{
    do_action('ur_do_an_info_start');
    include UR_PLUGIN_VIEWS_DIR . '/do-an-info.php';
}

function link_to_result_page()
{
    do_action('ur_do_an_result_start');
    include UR_PLUGIN_VIEWS_DIR . '/do-an-result.php';
}

/**
 * Show a nocie after our custom bulk action is done
 */
function show_admin_notices()
{
    if (!empty($_REQUEST['changed_datetime'])) {
        $num_changed = (int)$_REQUEST['changed_datetime'];
        $message = sprintf('<div id="message" class="updated notice is-dismissable"><p>' . __('Đã thay đổi thời gian %d đồ án.', 'txtdomain') . '</p></div>', $num_changed);
        echo $message;
    }
}

function add_custom_scripts()
{
    global $current_screen;

    switch ($current_screen->post_type) {
        case UR_DO_AN:
?>
            <script type="text/javascript">
                jQuery(document).ready(function($) {
                    let change_date_div = $(
                        '<div class="alignleft actions bulk-datetime" style="display: none;" id="change_datetime">' +
                        '<label class="me-2">Ngày bắt đầu:</label>' +
                        '<input class="me-2" type="datetime-local" name="<?php echo UR_DO_AN; ?>_start_date" aria-label="Ngày bắt đầu" title="Ngày bắt đầu" value="">' +
                        '<label class="me-2">Ngày bắt đầu:</label>' +
                        '<input type="datetime-local" name="<?php echo UR_DO_AN; ?>_end_date" aria-label="Ngày kết thúc" title="Ngày kết thúc" value="">' +
                        '</div>'
                    );
                    change_date_div.insertAfter('#bulk-action-selector-top');
                });
            </script>
            <?php
            break;
    }
}

function change_quick_edit(string $column_name, string $post_type, $taxanomy)
{
    if ($post_type == UR_DO_AN) {
        global $post;
        $id = $post->ID;

        switch ($column_name) {
            case UR_DO_AN . '_instructor':
                $instructor = get_post_meta($id, UR_DO_AN . '_instructor', true);
                $list_instructors = ur_Info::get_all_instructors();
            ?>
                <fieldset class="inline-edit-col-right" id="edit-instructor">
                    <legend><span class="title">Thông tin đồ án</span></legend>
                    <div class="row">
                        <label class="col-4 form-label">Giảng viên hướng dẫn:</label>
                        <div class="col">
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
                    </div>
                <?php
                break;
            case UR_DO_AN . '_type':
                $type = get_post_meta($id, UR_DO_AN . '_type', true);
                ?>
                    <div class="row">
                        <label class="col-4 form-label">Loại:</label>
                        <div class="col">
                            <select class="form-control" name="<?php echo UR_DO_AN; ?>_type">
                                <option value="<?php echo DO_AN_CO_SO; ?>" <?php echo $type == DO_AN_CO_SO ? 'selected' : ''; ?>>Đồ án cơ sở</option>
                                <option value="<?php echo DO_AN_CHUYEN_NGANH; ?>" <?php echo $type == DO_AN_CHUYEN_NGANH ? 'selected' : ''; ?>>Đồ án chuyên ngành</option>
                            </select>
                        </div>
                    </div>
                <?php
                break;
            case UR_DO_AN . '_class':
                $classes = explode(', ', get_post_meta($id, UR_DO_AN . '_class', true));
                $list_classes = ur_Info::get_all_classes();
                ?>
                    <div class="row">
                        <label class="col-4 form-label">Lớp:
                            <p><i>(Nhấn giữ Ctrl để chọn nhiều lớp)</i></p>
                        </label>
                        <div class="col">
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
                <?php
                break;
            case UR_DO_AN . '_start_date':
                $start_date = get_post_meta($id, UR_DO_AN . '_start_date', true);
                ?>
                    <div class="row mt-2">
                        <label class="col-4 form-label">Ngày bắt đầu đăng ký:</label>
                        <div class="col">
                            <input class="form-control" type="datetime-local" name="<?php echo UR_DO_AN; ?>_start_date" value="<?php echo $start_date; ?>" aria-label="Ngày bắt đầu" title="Ngày bắt đầu">
                        </div>
                    </div>
                <?php
                break;
            case UR_DO_AN . '_end_date':
                $end_date = get_post_meta($id, UR_DO_AN . '_end_date', true);
                ?>
                    <div class="row">
                        <label class="col-4 form-label">Ngày kết thúc đăng ký:</label>
                        <div class="col">
                            <input class="form-control" type="datetime-local" name="<?php echo UR_DO_AN; ?>_end_date" value="<?php echo $end_date; ?>" aria-label="Ngày kết thúc" title="Ngày kết thúc">
                        </div>
                    </div>
                </fieldset>
<?php
                break;
        }
    }
}

function save_quick_edit(string|int $post_id)
{
    // check inlint edit nonce
    if (isset($_POST['_inline_edit']) && !wp_verify_nonce($_POST['_inline_edit'], 'inlineeditnonce')) {
        return;
    }

    $instructor = !empty($_POST[UR_DO_AN . '_instructor']) ? $_POST[UR_DO_AN . '_instructor'] : '';
    $type = !empty($_POST[UR_DO_AN . '_type']) ? $_POST[UR_DO_AN . '_type'] : '';
    $classes = !empty($_POST[UR_DO_AN . '_class']) ? $_POST[UR_DO_AN . '_class'] : array();
    $start_date = !empty($_POST[UR_DO_AN . '_start_date']) ? $_POST[UR_DO_AN . '_start_date'] : '';
    $end_date = !empty($_POST[UR_DO_AN . '_end_date']) ? $_POST[UR_DO_AN . '_end_date'] : '';

    update_post_meta($post_id, UR_DO_AN . '_instructor', $instructor);
    update_post_meta($post_id, UR_DO_AN . '_type', $type);
    update_post_meta($post_id, UR_DO_AN . '_class', implode(', ', $classes));
    update_post_meta($post_id, UR_DO_AN . '_start_date', $start_date);
    update_post_meta($post_id, UR_DO_AN . '_end_date', $end_date);
}

function register_api()
{
    register_rest_route(
        'api/v1', // prefix with version
        '/registration/', // name of the route
        array(
            'methods' => 'POST',
            'callback' => 'registration',
        )
    );
    register_rest_route(
        'api/v1',
        '/export/',
        array(
            'methods' => 'GET',
            'callback' => 'export_result',
        )
    );
}

function load_custom_filter_metadata()
{
    global $typenow;

    if ($typenow == UR_DO_AN) {
        add_filter('posts_where', 'do_an_where_query');
    }
}

function do_an_where_query($where)
{
    global $wpdb;
    $meta_key = UR_DO_AN . '_type';

    if (isset($_GET['type']) && !empty($_GET['type'])) {
        $type = $_GET['type'];
        $where .= " AND ID IN (SELECT post_id FROM $wpdb->postmeta WHERE meta_key='$meta_key' AND meta_value='$type' )";
    }
    return $where;
}

?>
<?php

/**
 * Hook filter
 * 
 * @version  1.0.0
 * @package CustomUserRegistration
 */

/**
 * WordPress offers filter hooks to allow plugins to modify various types of internal data at runtime.
 * A plugin can modify data by binding a callback to a filter hook. When the filter is later applied, 
 * each bound callback is run in order of priority, and given the opportunity to modify a value by returning a new value.
 */

require_once UR_PLUGIN_MODELS_DIR . '/Constants.php';

/**
 * add_filter( string $hook_name, callable $callback, int $priority = 10, int $accepted_args = 1 )
 * Adds a callback function to a filter hook.
 * 
 * Parameters
 * $hook_name: (string) (Required) The name of the filter to add the callback to.
 * $callback: (callable) (Required) The callback to be run when the filter is applied.
 * $priority: (int) (Optional) Used to specify the order in which the functions associated with 
 * a particular filter are executed. Lower numbers correspond with earlier execution, and functions 
 * with the same priority are executed in the order in which they were added to the filter.
 * Default value: 10
 * $accepted_args: (int) (Optional) The number of arguments the function accepts.
 * Default value: 1

 * Return
 * (true) Always returns true.
 */

add_filter('manage_edit-' . UR_DO_AN . '_columns', 'customize_columns_in_ur_do_an');
add_filter('manage_edit-' . UR_DO_AN . '_sortable_columns', 'sortable_columns_ur_do_an');
add_filter('request', 'sort_columns_ur_do_an');
add_filter('bulk_actions-edit-' . UR_DO_AN, 'add_bulk_action');
add_filter('handle_bulk_actions-edit-' . UR_DO_AN, 'change_datetime', 10, 3);
add_filter('views_edit-' . UR_DO_AN, 'add_meta_view', 10, 1);

/**
 * Add new columns and hide specific columns
 */
function customize_columns_in_ur_do_an($columns)
{
    // Hide column
    unset($columns['date']);
    unset($columns['author']);

    // Add new columns
    $column_type = array(
        UR_DO_AN . '_instructor' => 'Giảng viên hướng dẫn',
        UR_DO_AN . '_type' => 'Loại đồ án',
        UR_DO_AN . '_class' => 'Lớp',
        UR_DO_AN . '_start_date' => 'Ngày bắt đầu đăng ký',
        UR_DO_AN . '_end_date' => 'Ngày kết thúc đăng ký',
        UR_DO_AN . '_status' => 'Trạng thái đã đăng ký'
    );
    return array_slice($columns, 0, 6, true) + $column_type + array_slice($columns, 6, NULL, true);
}

/**
 * Mark these columns as sortable
 */
function sortable_columns_ur_do_an($columns)
{
    $columns[UR_DO_AN . '_instructor'] = UR_DO_AN . '_instructor';
    $columns[UR_DO_AN . '_type'] = UR_DO_AN . '_type';
    $columns[UR_DO_AN . '_class'] = UR_DO_AN . '_class';
    $columns[UR_DO_AN . '_start_date'] = UR_DO_AN . '_start_date';
    $columns[UR_DO_AN . '_end_date'] = UR_DO_AN . '_end_date';
    $columns[UR_DO_AN . '_status'] = UR_DO_AN . '_status';
    return $columns;
}

/**
 * Criteria for sorting ur_do_an
 */
function sort_columns_ur_do_an($vars)
{
    if (isset($vars['orderby'])) {
        if (UR_DO_AN . '_type' == $vars['orderby']) {
            $vars = array_merge($vars, array(
                'meta_key' => UR_DO_AN . '_type',
                'orderby' => 'meta_value'
            ));
        } else if (UR_DO_AN . '_class' == $vars['orderby']) {
            $vars = array_merge($vars, array(
                'meta_key' => UR_DO_AN . '_class',
                'orderby' => 'meta_value'
            ));
        } else if (UR_DO_AN . '_start_date' == $vars['orderby']) {
            $vars = array_merge($vars, array(
                'meta_key' => UR_DO_AN . '_start_date',
                'orderby' => 'meta_value_num'
            ));
        } else if (UR_DO_AN . '_end_date' == $vars['orderby']) {
            $vars = array_merge($vars, array(
                'meta_key' => UR_DO_AN . '_end_date',
                'orderby' => 'meta_value_num'
            ));
        } else if (UR_DO_AN . '_status' == $vars['orderby']) {
            $vars = array_merge($vars, array(
                'meta_key' => UR_DO_AN . '_status',
                'orderby' => 'meta_value_num'
            ));
        } else if (UR_DO_AN . '_instructor' == $vars['orderby']) {
            $vars = array_merge($vars, array(
                'meta_key' => UR_DO_AN . '_instructor',
                'orderby' => 'meta_value'
            ));
        }
    }
    return $vars;
}

/**
 * Create a new bulk action for ur_do_an
 */
function add_bulk_action($bulk_actions)
{
    $bulk_actions['change-datetime'] = __('Thay đổi thời gian', 'txtdomain');
    return $bulk_actions;
}

/**
 * Define action for new bulk action
 */
function change_datetime($redirect_url, $action, $post_ids)
{
    $start_date = $_GET[UR_DO_AN . '_start_date'];
    $end_date = $_GET[UR_DO_AN . '_end_date'];

    $redirect_url = remove_query_arg(array('change-datetime'), $redirect_url);

    if ($action == 'change-datetime' && isset($start_date) && isset($end_date)) {
        foreach ($post_ids as $post_id) {
            update_post_meta($post_id, UR_DO_AN . '_start_date', $start_date);
            update_post_meta($post_id, UR_DO_AN . '_end_date', $end_date);
        }
        $redirect_url = add_query_arg('changed_datetime', count($post_ids), $redirect_url);
    }
    return $redirect_url;
}

function add_meta_view($views)
{
    $views['metakey_dacs'] = '<a href="edit.php?type=' . DO_AN_CO_SO . '&post_type=' . UR_DO_AN . '">Đồ án cơ sở</a>';
    $views['metakey_dacn'] = '<a href="edit.php?type=' . DO_AN_CHUYEN_NGANH . '&post_type=' . UR_DO_AN . '">Đồ án chuyên ngành</a>';
    return $views;
}

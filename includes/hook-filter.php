<?php

require_once UR_PLUGIN_MODELS_DIR . '/Constants.php';

add_filter('manage_edit-' . UR_DO_AN . '_columns', 'customize_columns_in_ur_do_an');
add_filter('manage_edit-' . UR_DO_AN . '_sortable_columns', 'sortable_columns_ur_do_an');
add_filter('request', 'sort_columns_ur_do_an');
add_filter('bulk_actions-edit-' . UR_DO_AN, 'add_bulk_action');
add_filter('handle_bulk_actions-edit-' . UR_DO_AN, 'change_datetime');

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
        'instructor' => 'Giảng viên hướng dẫn',
        'type' => 'Loại đồ án',
        'start_date' => 'Ngày bắt đầu đăng ký',
        'end_date' => 'Ngày kết thúc đăng ký',
        'status' => 'Trạng thái đã đăng ký'
    );
    return array_slice($columns, 0, 6, true) + $column_type + array_slice($columns, 6, NULL, true);
}

/**
 * Mark these columns as sortable
 */
function sortable_columns_ur_do_an($columns)
{
    $columns['instructor'] = 'instructor';
    $columns['type'] = 'type';
    $columns['start_date'] = 'start_date';
    $columns['end_date'] = 'end_date';
    $columns['status'] = 'status';
    return $columns;
}

/**
 * Criteria for sorting ur_do_an
 */
function sort_columns_ur_do_an($vars)
{
    if (isset($vars['orderby']) && 'type' == $vars['orderby']) {
        $vars = array_merge($vars, array(
            'meta_key' => 'type',
            'orderby' => 'meta_value'
        ));
    } else if (isset($vars['orderby']) && 'start_date' == $vars['orderby']) {
        $vars = array_merge($vars, array(
            'meta_key' => 'start_date',
            'orderby' => 'meta_value_num'
        ));
    } else if (isset($vars['orderby']) && 'end_date' == $vars['orderby']) {
        $vars = array_merge($vars, array(
            'meta_key' => 'end_date',
            'orderby' => 'meta_value_num'
        ));
    } else if (isset($vars['orderby']) && 'status' == $vars['orderby']) {
        $vars = array_merge($vars, array(
            'meta_key' => 'status',
            'orderby' => 'meta_value_num'
        ));
    } else if (isset($vars['orderby']) && 'instructor' == $vars['orderby']) {
        $vars = array_merge($vars, array(
            'meta_key' => 'instructor',
            'orderby' => 'meta_value'
        ));
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
    if ($action == 'change-datetime') {
        foreach ($post_ids as $post_id) {
            update_post_meta($post_id, 'start_date', '');
            update_post_meta($post_id, 'end_date', '');
        }
        $redirect_url = add_query_arg('changed-datetime', count($post_ids), $redirect_url);
    }
}

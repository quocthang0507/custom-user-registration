<?php

// Add extra columns for custom post type (ur_do_an)
// Phải dùng dấu gạch ngang để nối tiền tố manage_edit-{post_type}_...

// Loại đồ án

add_filter('manage_edit-ur_do_an_columns', 'add_custom_columns_to_ur_do_an');
add_filter('manage_edit-ur_do_an_sortable_columns', 'sortable_columns_ur_do_an');
add_filter('request', 'sort_columns_ur_do_an');

function add_custom_columns_to_ur_do_an($columns)
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

function sortable_columns_ur_do_an($columns)
{
    $columns['instructor'] = 'instructor';
    $columns['type'] = 'type';
    $columns['start_date'] = 'start_date';
    $columns['end_date'] = 'end_date';
    $columns['status'] = 'status';
    return $columns;
}

function sort_columns_ur_do_an($vars)
{
    if (isset($vars['orderby']) && 'type' == $vars['orderby']) {
        $vars = array_merge($vars, array(
            'meta_key' => 'type',
            'orderby' => 'meta_value'
        ));
    }
    else if (isset($vars['orderby']) && 'start_date' == $vars['orderby']) {
        $vars = array_merge($vars, array(
            'meta_key' => 'start_date',
            'orderby' => 'meta_value_num'
        ));
    }
    else if (isset($vars['orderby']) && 'end_date' == $vars['orderby']) {
        $vars = array_merge($vars, array(
            'meta_key' => 'end_date',
            'orderby' => 'meta_value_num'
        ));
    }
    else if (isset($vars['orderby']) && 'status' == $vars['orderby']) {
        $vars = array_merge($vars, array(
            'meta_key' => 'status',
            'orderby' => 'meta_value_num'
        ));
    }
    else if (isset($vars['orderby']) && 'instructor' == $vars['orderby']) {
        $vars = array_merge($vars, array(
            'meta_key' => 'instructor',
            'orderby' => 'meta_value'
        ));
    }
    return $vars;
}

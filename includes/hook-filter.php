<?php

// Add extra columns for custom post type (ur_do_an)
// Phải dùng dấu gạch ngang - để nối tiền tố manage_edit

add_filter('manage_edit-ur_do_an_columns', 'add_new_columns_to_ur_do_an');
add_filter('manage_edit-ur_do_an_sortable_columns', 'sortable_columns_ur_do_an');
add_filter('request', 'sort_columns_ur_do_an');

function add_new_columns_to_ur_do_an($columns)
{
    $column_type = array('type' => 'Loại đồ án');
    return array_slice($columns, 0, 6, true) + $column_type + array_slice($columns, 6, NULL, true);
}

function sortable_columns_ur_do_an($columns)
{
    $columns['type'] = 'type';
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
    return $vars;
}

<?php

// Enqueue scripts for all admin page
add_action('admin_enqueue_scripts', 'load_plugin_css');

function load_plugin_css()
{
    wp_register_style('style', plugins_url('custom-user-registration/css/style.css'), __FILE__);
    wp_enqueue_style('style');
}

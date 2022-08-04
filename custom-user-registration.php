<?php
/*
Plugin Name: Custom User Registration
PLugin URI: https://github.com/quocthang0507/user-registration
Description: A custom registration form
Version: 1.0.0
Author: La Quoc Thang
Author URI: https://github.com/quocthang0507
License: GPL3
License URI: https://www.gnu.org/licenses/gpl-3.0.html
Text Domain: custom-user-registration
*/

defined('ABSPATH') or die('No script kiddies pleases!');

define('UR_PATH', plugin_dir_path(__FILE__));

define('UR_VERSION', '1.0.0');

define('UR_URL', plugin_dir_url(__FILE__));

define('UR_PLUGIN', __FILE__);

define('UR_PLUGIN_BASENAME', plugin_basename(UR_PLUGIN));

define('UR_PLUGIN_NAME', trim(dirname(UR_PLUGIN_BASENAME), '/'));

define('UR_PLUGIN_DIR', untrailingslashit(dirname(UR_PLUGIN)));

define('UR_PLUGIN_MODELS_DIR', UR_PLUGIN_DIR . '/models');

define('UR_PLUGIN_VIEWS_DIR', UR_PLUGIN_DIR . '/views');

define('UR_PLUGIN_PAGES_DIR', UR_PLUGIN_DIR . '/forms');

define('UR_PLUGIN_CSS_DIR', UR_PLUGIN_DIR . '/css');

define('UR_PLUGIN_INCLUDES_DIR', UR_PATH . '/includes');

require_once UR_PLUGIN_INCLUDES_DIR . '/generate-do-an-post-type.php';
require_once UR_PATH . '/includes/hook-action.php';
require_once UR_PATH . '/includes/hook-filter.php';
require_once UR_PLUGIN_PAGES_DIR . '/form_do_an.php';

init_ur_do_an();

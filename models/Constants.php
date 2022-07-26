<?php

/**
 * Constants
 * 
 * @version  1.0.0
 * @package CustomUserRegistration
 */

if (!defined('REGISTER_FORM_ACTION'))
    define('REGISTER_FORM_ACTION', 'register_form');

// Meta keys

if (!defined('DO_AN_CO_SO'))
    define('DO_AN_CO_SO', 'do_an_co_so');

if (!defined('DO_AN_CHUYEN_NGANH'))
    define('DO_AN_CHUYEN_NGANH', 'do_an_chuyen_nganh');

if (!defined('GENERATE_DO_AN_POST_TYPE'))
    define('GENERATE_DO_AN_POST_TYPE', 'generate_do_an_post_type');

if (!defined('DO_AN_METABOX'))
    define('DO_AN_METABOX', 'ur_do_an_metabox');

if (!defined('UR_DO_AN'))
    define('UR_DO_AN', 'ur_do_an');

if (!defined('UR_INSTRUCTORS_META_KEY'))
    define('UR_INSTRUCTORS_META_KEY', 'ur_instructors');

if (!defined('UR_CLASSES_META_KEY'))
    define('UR_CLASSES_META_KEY', 'ur_classes');

if (!defined('UR_REGISTER_DO_AN_META_KEY'))
    define('UR_REGISTER_DO_AN_META_KEY', UR_DO_AN . '_registration');

// Menu và các trang quản trị

if (!defined('MENU_QUAN_LY_DO_AN'))
    define('MENU_QUAN_LY_DO_AN', 'Quản lý đồ án');

if (!defined('MENU_ADD_DO_AN'))
    define('MENU_ADD_DO_AN', 'Thêm đồ án');

if (!defined('MENU_EDIT_DO_AN'))
    define('MENU_EDIT_DO_AN', 'Chỉnh sửa đồ án');

if (!defined('MENU_NOT_FOUND_DO_AN'))
    define('MENU_NOT_FOUND_DO_AN', 'Không tìm thấy đồ án');

if (!defined('MENU_NOT_FOUND_TRASH_DO_AN'))
    define('MENU_NOT_FOUND_TRASH_DO_AN', 'Không tìm thấy đồ án trong thùng rác');

if (!defined('MENU_ALL_DO_AN'))
    define('MENU_ALL_DO_AN', 'Tất cả đồ án');

if (!defined('DESCRIPTION_DO_AN'))
    define('DESCRIPTION_DO_AN', 'Quản lý Đồ án');

if (!defined('REGISTERED_DO_AN'))
    define('REGISTERED_DO_AN', 'Danh sách đăng ký');

if (!defined('INFO_DO_AN'))
    define('INFO_DO_AN', 'Lớp & GVHD');

if (!defined('IMPORT_DO_AN'))
    define('IMPORT_DO_AN', 'Nhập danh sách');

// Messages

if (!defined('DO_AN_UNAVAILABLE'))
    define('DO_AN_UNAVAILABLE', 'Đồ án không khả dụng');

if (!defined('DO_AN_HAS_REGISTERED'))
    define('DO_AN_HAS_REGISTERED', 'Đồ án đã đăng ký rồi');

if (!defined('DO_AN_INTERNAL_ERROR'))
    define('DO_AN_INTERNAL_ERROR', 'Đồ án không thể đăng ký do lỗi hệ thống');

// Constants for datetime

if (!defined('rfc_3339'))
    define('rfc_3339', 'Y-m-d\TH:i');

if (!defined('vi_datetime'))
    define('vi_datetime', 'd/m/Y H:i');

if (!defined('iso_date'))
    define('iso_date', 'Y-m-d');

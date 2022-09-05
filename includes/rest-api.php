<?php

require_once UR_PLUGIN_MODELS_DIR . '/DangKy.php';
require_once UR_PLUGIN_MODELS_DIR . '/Constants.php';

function registration(WP_REST_Request $request)
{
    $response = new WP_REST_Response(array());
    $response->set_status(400); // bad request

    if (!is_user_logged_in()) {
        $response->set_status(401); // unauthorized
        return $response;
    }

    if (isset($request['action']) && isset($request['post_id'])) {
        $user_id = get_current_user_id();
        $result = false;

        $action = $request['action'];
        $post_id = $request['post_id'];

        if ($action == 'register') {
            $result = ur_DangKy::register($user_id, $post_id);
        } else if ($action == 'unregister') {
            $result = ur_DangKy::unregister($user_id, $post_id);
        }
        if ($result) {
            $response->set_status(200); // ok
        }
    }
    return $response;
}

function export_result(WP_REST_Request $request)
{
    if (!is_user_logged_in()) {
        header('HTTP/1.0 401 Unauthorized');
        exit;
    }

    $instructor = isset($request[UR_DO_AN . '_instructor']) ? $request[UR_DO_AN . '_instructor'] : 'all';
    $type = isset($request[UR_DO_AN . '_type']) ? $request[UR_DO_AN . '_type'] : 'all';
    $class = isset($request[UR_DO_AN . '_class']) ? $request[UR_DO_AN . '_class'] : 'all';
    $semester = isset($request[UR_DO_AN . '_semester']) ? $request[UR_DO_AN . '_semester'] : 'all';
    $schoolyear = isset($request[UR_DO_AN . '_schoolyear']) ? $request[UR_DO_AN . '_schoolyear'] : 'all';

    // Convert đồ án to string
    $list_do_an = ur_DoAn::get_list_do_an($type, $instructor, $schoolyear, $semester, $class);
    $do_an = new ur_DoAn(null, null);
    $text = $do_an->ToString(true); // export csv's title
    foreach ($list_do_an as $do_an) {
        $text .= PHP_EOL . $do_an->ToString();
    }

    date_default_timezone_set('Asia/Ho_Chi_Minh');
    $filename = 'doan_' . date(vi_datetime) . '.csv';
    header("Access-Control-Expose-Headers: Content-Disposition", false);
    header('Content-type: text/csv');
    header("Content-Disposition: attachment; filename=\"$filename\"");
    
    $csv_file = fopen('php://output', 'w');
    fwrite($csv_file, $text);
    fclose($csv_file);

    exit;
}

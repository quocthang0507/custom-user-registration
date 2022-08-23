<?php

require_once UR_PLUGIN_MODELS_DIR . '/DangKy.php';

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
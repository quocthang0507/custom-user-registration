<?php

function registration_form($first_name, $last_name, $student_id, $student_class)
{
    echo '
    <style>
        div {
            margin-bottom: 2px;
        }
        input {
            margin-bottom: 4px;
        }
    </style>
    ';

    echo '
    <form action="' . $_SERVER['REQUEST_URI'] . '" method="post">
        <div>
            <label for="first_name">First name <strong>*</strong> </label>
            <input type="text" name="first_name" value="' . (isset($_POST['first_name']) ? $first_name : null) . '";
        </div>
        <div>
            <label for="last_name">Last name <strong>*</strong> </label>
            <input type="text" name="last_name" value="' . (isset($_POST['last_name']) ? $last_name : null) . '";
        </div>
        <div>
            <label for="student_id">Student ID <strong>*</strong> </label>
            <input type="text" name="student_id" value="' . (isset($_POST['student_id']) ? $student_id : null) . '";
        </div>
        <div>
            <label for="student_class">Class <strong>*</strong> </label>
            <input type="text" name="student_class" value="' . (isset($_POST['student_class']) ? $student_class : null) . '";
        </div>
        <input type="submit" name="submit" value="Register"/>
    </form>
    ';
}

function validation($first_name, $last_name, $student_id, $student_class)
{
    global $reg_errors;
    $reg_errors = new WP_Error;

    if (empty($first_name) || empty($last_name) || empty($student_id) || empty($student_class)) {
        $reg_errors->add('field', 'Required form field is missing');
    }

    //... 

    if (is_wp_error($reg_errors)) {
        foreach ($reg_errors->get_error_messages() as $error) {
            echo '
            <div>
                <strong>Error:</strong>' .
                $error . '<br/>
            </div>
            ';
        }
    }
}

function complete_registration()
{
    global $reg_errors, $first_name, $last_name, $student_id, $student_class;
    if (1 > count($reg_errors->get_error_messages())) {
        $userdata = array(
            'first_name' => $first_name,
            'last_name' => $last_name,
            'student_id' => $student_id,
            'student_class' => $student_class,
        );
        $user = wp_insert_user($userdata);
        echo 'Completed. Go to <a href="' . get_site_url() . '>home page</a>';
    }
}

function custom_registration_function()
{
    if (isset($_POST['submit'])) {
        validation(
            $_POST['first_name'],
            $_POST['last_name'],
            $_POST['student_id'],
            $_POST['student_class']
        );

        global $first_name, $last_name, $student_id, $student_class;
        $first_name = sanitize_text_field($_POST['first_name']);
        $last_name = sanitize_text_field($_POST['last_name']);
        $student_id = sanitize_text_field($_POST['student_id']);
        $student_class = sanitize_text_field($_POST['student_class']);

        complete_registration(
            $first_name,
            $last_name,
            $student_id,
            $student_class
        );
    }

    registration_form(
        $first_name,
        $last_name,
        $student_id,
        $student_class
    );
}

add_shortcode('ur_form_do_an', 'custom_registration_form_do_an_shortcode');

function custom_registration_form_do_an_shortcode()
{
    ob_start();
    custom_registration_function();
    return ob_get_clean();
}

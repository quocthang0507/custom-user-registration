<?php

/**
 * Class DoAn
 * 
 * @version  1.0.0
 * @package CustomUserRegistration
 */

use function PHPSTORM_META\type;

require_once 'Constants.php';
require_once UR_PLUGIN_INCLUDES_DIR . '/utils.php';

class ur_DoAn
{
    public int $ID;
    public string $post_title;
    public string $description;
    public string $instructor;
    public int $max_students;
    public string $references;
    public string $start_date;
    public string $end_date;
    public string $schoolyear;
    public string $class;
    public string $type;
    // public $registration;

    /**
     * PHP doesn't have overloading
     */
    public function __construct($post, $metadata, bool $only_id = false)
    {
        $description = UR_DO_AN . '_description';
        $instructor = UR_DO_AN . '_instructor';
        $max_students = UR_DO_AN . '_max_students';
        $references = UR_DO_AN . '_references';
        $start_date = UR_DO_AN . '_start_date';
        $end_date = UR_DO_AN . '_end_date';
        $schoolyear = UR_DO_AN . '_schoolyear';
        $semester = UR_DO_AN . '_semester';
        $class = UR_DO_AN . '_class';
        $type = UR_DO_AN . '_type';
        // Chỉ tạo đối tượng có id và tên
        if ($only_id) {
            $this->ID = $post->ID;
            $this->post_title = $post->post_title;
        } else if ($metadata == null) {
            // Nếu thông tin gửi qua phương thức POST
            $this->ID = sanitize_text_field($post['ID']);
            $this->post_title = sanitize_text_field($post['post_title']);
            $this->description = sanitize_text_field($post[$description]);
            $this->instructor = sanitize_text_field($post[$instructor]);
            $this->max_students = sanitize_text_field($post[$max_students]);
            $this->references = sanitize_text_field($post[$references]);
            $this->start_date = sanitize_text_field($post[$start_date]);
            $this->end_date = sanitize_text_field($post[$end_date]);
            $this->schoolyear = sanitize_text_field($post[$schoolyear]);
            $this->semester = sanitize_text_field($post[$semester]);
            $this->class = sanitize_text_field($post[$class]);
            $this->type = sanitize_text_field($post[$type]);
        } else {
            // Nếu thông tin có cả metadata
            $this->ID = $post->ID;
            $this->post_title = $post->post_title;
            $this->description = $metadata->$description[0];
            $this->instructor = $metadata->$instructor[0];
            $this->max_students = $metadata->$max_students[0];
            $this->references = $metadata->$references[0];
            $this->start_date = $metadata->$start_date[0];
            $this->end_date = $metadata->$end_date[0];
            $this->schoolyear = $metadata->$schoolyear[0];
            $this->semester = $metadata->$semester[0];
            $this->class = $metadata->$class[0];
            $this->type = $metadata->$type[0];
        }
    }

    /**
     * Return number of registration
     */
    public function get_count_registration()
    {
        $registered_students = get_post_meta($this->ID, UR_REGISTER_DO_AN_META_KEY, true);
        if ($registered_students != null && is_array($registered_students))
            return count($registered_students);
        return 0;
    }

    /**
     * Check current datetime is available
     */
    public function is_available_date()
    {
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $current_date = new DateTime();
        $start_date = new DateTime($this->start_date);
        $end_date = new DateTime($this->end_date);
        return $current_date >= $start_date && $current_date <= $end_date;
    }

    /**
     * Check sure đồ án available for registering
     */
    public function is_available()
    {
        return $this->is_available_date() && $this->get_count_registration() < $this->max_students;
    }

    /**
     * Get đồ án by post id
     */
    public static function get_do_an_by_id(int $post_id, bool $only_id = false)
    {
        $post = get_post($post_id);
        if ($only_id)
            return new ur_DoAn($post, null, true);
        $metadata = (object)get_post_meta($post_id);
        return new ur_DoAn($post, $metadata);
    }

    /**
     * Get list đồ án by multiple conditions
     */
    public static function get_list_do_an(string $type = 'all', string $instructor = 'all', string $schoolyear = 'all', string $semester = 'all', string $class = 'all')
    {
        // Initialize criteria
        $criteria = array(
            'relation' => 'AND',
        );
        if ($type != "all")
            array_push($criteria, array(
                'key' => UR_DO_AN . '_type',
                'value' => $type,
                'compare' => '='
            ));
        if ($instructor != "all")
            array_push($criteria, array(
                'key' => UR_DO_AN . '_instructor',
                'value' => $instructor,
                'compare' => '='
            ));
        if ($schoolyear != "all")
            array_push($criteria, array(
                'key' => UR_DO_AN . '_schoolyear',
                'value' => $schoolyear,
                'compare' => '='
            ));
        if ($semester != "all")
            array_push($criteria, array(
                'key' => UR_DO_AN . '_semester',
                'value' => $semester,
                'compare' => '='
            ));
        if ($class != "all")
            array_push($criteria, array(
                'key' => UR_DO_AN . '_class',
                'value' => $class,
                'compare' => '='
            ));

        $args = array(
            'post_type' => UR_DO_AN,
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'meta_query' => $criteria
        );
        // Cách 1
        $query = new WP_Query($args);
        $posts = $query->posts;
        $result = array();
        foreach ($posts as $post) {
            $arr = self::get_do_an_by_id($post->ID);
            if ($arr != null)
                array_push($result, $arr);
        }
        return $result;
    }

    /**
     * Get list of đồ án available
     */
    public static function get_list_do_an_available(string $type, bool $only_id = false)
    {
        date_default_timezone_set('Asia/Ho_Chi_Minh');

        $args = array(
            'post_type' => UR_DO_AN,
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'meta_query' => array(
                'relation' => 'AND',
                array(
                    'key' => UR_DO_AN . '_type',
                    'value' => $type,
                    'compare' => '='
                ),
                array(
                    'key' => UR_DO_AN . '_start_date',
                    'value' => date('Y-m-d\TH:i'),
                    'compare' => '<=',
                ),
                array(
                    'key' => UR_DO_AN . '_end_date',
                    'value' => date('Y-m-d\TH:i'),
                    'compare' => '>=',
                )
            )
        );
        // Cách 2
        $posts = get_posts($args);
        $result = array();
        foreach ($posts as $post) {
            $obj = self::get_do_an_by_id($post->ID, $only_id);
            if ($obj != null)
                array_push($result, $obj);
        }
        return $result;
    }

    /**
     * Insert đồ án
     */
    public static function insert_do_an($post)
    {
        return wp_insert_post($post);
    }

    /**
     * Update đồ án
     */
    public static function update_do_an(int $post_id, $title, $metadata)
    {
        try {
            // Update post title
            if ($title != null) {
                $data = array(
                    'ID' => $post_id,
                    'post_title' => $title,
                );
                wp_update_post($data);
            }

            // Update metadata post
            if ($metadata != null) {
                update_post_meta($post_id, UR_DO_AN . '_description', $metadata->description);
                update_post_meta($post_id, UR_DO_AN . '_instructor', $metadata->instructor);
                update_post_meta($post_id, UR_DO_AN . '_max_students', $metadata->max_students);
                update_post_meta($post_id, UR_DO_AN . '_references', $metadata->references);
                update_post_meta($post_id, UR_DO_AN . '_start_date', $metadata->start_date);
                update_post_meta($post_id, UR_DO_AN . '_end_date', $metadata->end_date);
                update_post_meta($post_id, UR_DO_AN . '_schoolyear', $metadata->schoolyear);
                update_post_meta($post_id, UR_DO_AN . '_semester', $metadata->semester);
                update_post_meta($post_id, UR_DO_AN . '_class', $metadata->class);
                update_post_meta($post_id, UR_DO_AN . '_type', $metadata->type);
            }
        } catch (Exception $e) {
            return false;
        }
        return true;
    }
}

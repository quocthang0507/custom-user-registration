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
    public $ID;
    public $post_title;
    public $description;
    public $instructor;
    public $max_students;
    public $references;
    public $start_date;
    public $end_date;
    public $schoolyear;
    public $class;
    public $type;

    /**
     * PHP doesn't have overloading
     */
    public function __construct($post, $metadata)
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

        if ($metadata == null) {
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
     * Get do an by post id
     */
    public static function get_do_an_by_id(int $post_id)
    {
        $post = get_post($post_id);
        $metadata = (object)get_post_meta($post_id);
        return new ur_DoAn($post, $metadata);
    }

    /**
     * Get list do an by multiple conditions
     */
    public static function get_list_do_an(string $type, string $schoolyear, string $semester, string $class)
    {
        $args = array(
            'post_type' => UR_DO_AN,
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'meta_query' => array(
                'relation' => 'AND',
                array(
                    'key' => UR_DO_AN . '_schoolyear',
                    'value' => $schoolyear,
                    'compare' => '='
                ),
                array(
                    'key' => UR_DO_AN . '_semester',
                    'value' => $semester,
                    'compare' => '='
                ),
                array(
                    'key' => UR_DO_AN . '_class',
                    'value' => $class,
                    'compare' => '='
                ),
                array(
                    'key' => UR_DO_AN . '_type',
                    'value' => $type,
                    'compare' => '='
                ),
            )
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

    public static function get_list_do_an_avaiable(string $type)
    {
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
                    'type' => 'DATE'
                ),
                array(
                    'key' => UR_DO_AN . '_end_date',
                    'value' => date('Y-m-d\TH:i'),
                    'compare' => '>=',
                    'type' => 'DATE'
                )
            )
        );
        // Cách 2
        $posts = get_posts($args);
        $result = array();
        foreach ($posts as $post) {
            $obj = self::get_do_an_by_id($post->ID);
            if ($obj != null)
                array_push($result, $obj);
        }
        return $result;
    }

    /**
     * Get all do an chuyen nganh
     */
    public static function get_all_do_an_chuyen_nganh()
    {
        $args = array(
            'post_type' => UR_DO_AN,
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'meta_query' => array(
                array(
                    'key' => UR_DO_AN . '_type',
                    'value' => DO_AN_CHUYEN_NGANH,
                    'compare' => '='
                )
            )
        );
        $posts = get_posts($args);
        $result = array();
        foreach ($posts as $post) {
            $obj = self::get_do_an_by_id($post->ID);
            if ($obj != null)
                array_push($result, $obj);
        }
        return $result;
    }

    /**
     * Get list do an chuyen nganh by multiple conditions
     */
    public static function get_list_do_an_chuyen_nganh(string $schoolyear, string $semester, string $class)
    {
        return self::get_list_do_an(DO_AN_CHUYEN_NGANH, $schoolyear, $semester, $class);
    }

    /**
     * Get all do an co so
     */
    public static function get_all_do_an_co_so()
    {
        $args = array(
            'post_type' => UR_DO_AN,
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'meta_query' => array(
                array(
                    'key' => UR_DO_AN . '_type',
                    'value' => DO_AN_CO_SO,
                    'compare' => '='
                )
            )
        );
        $posts = get_posts($args);
        $result = array();
        foreach ($posts as $post) {
            $obj = self::get_do_an_by_id($post->ID);
            if ($obj != null)
                array_push($result, $obj);
        }
        return $result;
    }

    /**
     * Get list do an co so by multiple conditions
     */
    public static function get_list_do_an_co_so(string $schoolyear, string $semester, string $class)
    {
        return self::get_list_do_an(DO_AN_CO_SO, $schoolyear, $semester, $class);
    }

    /**
     * Insert do an
     */
    public static function insert_do_an($post)
    {
        return wp_insert_post($post);
    }

    /**
     * Update do an
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

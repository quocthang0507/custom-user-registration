<?php

require_once 'Constants.php';

class ur_DoAn
{
    private $ID;
    private $post_title;
    private $description;
    private $instructor;
    private $max_students;
    private $references;
    private $start_date;
    private $end_date;
    private $schoolyear;
    private $class;

    public function __construct($_POST)
    {
        $this->ID = sanitize_text_field($_POST['ID']);
        $this->post_title = sanitize_text_field($_POST['post_title']);
        $this->description = sanitize_text_field($_POST['description']);
        $this->instructor = sanitize_text_field($_POST['instructor']);
        $this->max_students = sanitize_text_field($_POST['max_students']);
        $this->references = sanitize_text_field($_POST['references']);
        $this->start_date = sanitize_text_field($_POST['start_date']);
        $this->end_date = sanitize_text_field($_POST['end_date']);
        $this->schoolyear = sanitize_text_field($_POST['schoolyear']);
        $this->semester = sanitize_text_field($_POST['semester']);
        $this->class = sanitize_text_field($_POST['class']);
    }

    public function __construct($post, $metadata)
    {
        $this->ID = $post->ID;
        $this->post_title = $post->post_title;
        $this->description = $metadata->description[0];
        $this->instructor = $metadata->instructor[0];
        $this->max_students = $metadata->max_students[0];
        $this->references = $metadata->references[0];
        $this->start_date = $metadata->start_date[0];
        $this->end_date = $metadata->end_date[0];
        $this->schoolyear = $metadata->schoolyear[0];
        $this->semester = $metadata->semester[0];
        $this->class = $metadata->class[0];
    }

    public static function get_do_an_by_id($post_id)
    {
        $data = get_post($post_id);
        $metadata = (object)get_post_meta($post_id);
        return new ur_DoAn($data, $metadata);
    }

    public static function get_list_do_an($loai_do_an, $nam_hoc, $hoc_ky, $lop)
    {
        $args = array(
            'post_type' => $loai_do_an,
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'meta_query' => array(
                'relation' => 'AND',
                array(
                    'key' => 'schoolyear',
                    'value' => $nam_hoc,
                    'compare' => '='
                ),
                array(
                    'key' => 'semester',
                    'value' => $hoc_ky,
                    'compare' => '='
                ),
                array(
                    'key' => 'class',
                    'value' => $lop,
                    'compare' => '='
                )
            )
        );
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

    public static function get_list_do_an_chuyen_nganh($nam_hoc, $hoc_ky, $lop)
    {
        return self::get_list_do_an(DO_AN_CHUYEN_NGANH, $nam_hoc, $hoc_ky, $lop);
    }

    public static function get_list_do_an_co_so($nam_hoc, $hoc_ky, $lop)
    {
        return self::get_list_do_an(DO_AN_CO_SO, $nam_hoc, $hoc_ky, $lop);
    }

    public static function insert_do_an($post)
    {
        return wp_insert_post($post);
    }

    public static function update_do_an($post_id, $title, $metadata)
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
                update_post_meta($post_id, 'description', $metadata->description);
                update_post_meta($post_id, 'instructor', $metadata->instructor);
                update_post_meta($post_id, 'max_students', $metadata->max_students);
                update_post_meta($post_id, 'references', $metadata->references);
                update_post_meta($post_id, 'start_date', $metadata->start_date);
                update_post_meta($post_id, 'end_date', $metadata->end_date);
                update_post_meta($post_id, 'schoolyear', $metadata->schoolyear);
                update_post_meta($post_id, 'semester', $metadata->semester);
                update_post_meta($post_id, 'class', $metadata->class);
            }
        } catch (Exception $e) {
            return false;
        }
        return true;
    }
}
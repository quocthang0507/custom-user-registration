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

    public function __construct($post_id, $post_title)
    {
        $this->ID = $post_id;
        $this->post_title = $post_title;
    }

    public function __construct($_POST)
    {
        $this->title = sanitize_text_field($_POST['post_title']);
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

    private static function post_type_to_array($post_id)
    {
        $data = get_post($post_id);
        $metadata = (object)get_post_meta($post_id);
        return self::get_post_type_to_array($data, $metadata);
    }

    private static function get_post_type_to_array($post, $metadata)
    {
        $result = array(
            'ID' => $post->ID,
            'post_title' => $post->post_title,
            'description' => $metadata->description[0],
            'instructor' => $metadata->instructor[0],
            'max_students' => $metadata->max_students[0],
            'references' => $metadata->references[0],
            'start_date' => DateTime::createFromFormat('d/m/Y', $metadata->start_date[0]),
            'end_date' => DateTime::createFromFormat('d/m/Y', $metadata->end_date[0]),
            'schoolyear' => $metadata->schoolyear[0],
            'semester' => $metadata->semester[0],
            'class' => $metadata->class[0],
        );

        return $result;
    }

    public static function get_do_an($loai_do_an, $nam_hoc, $hoc_ky, $lop)
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
            $arr = self::post_type_to_array($post->ID);
            if ($arr != null)
                array_push($result, $arr);
        }
        return $result;
    }

    public static function get_do_an_chuyen_nganh($nam_hoc, $hoc_ky, $lop)
    {
        return self::get_do_an(DO_AN_CHUYEN_NGANH, $nam_hoc, $hoc_ky, $lop);
    }

    public static function get_do_an_co_so($nam_hoc, $hoc_ky, $lop)
    {
        return self::get_do_an(DO_AN_CO_SO, $nam_hoc, $hoc_ky, $lop);
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

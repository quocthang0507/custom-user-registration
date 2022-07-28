<?php

require_once 'Constants.php';

class DoAn
{
    public static function post_type_to_array($post_id)
    {
        $data = get_post($post_id);
        $metadata = (object)get_post_meta($post_id);
        return self::get_post_type_to_array($data, $metadata);
    }

    public static function get_post_type_to_array($post, $metadata)
    {
        $result = array(
            'ID' => $post->ID,
            'TenDeTai' => $post->post_title,
            'MoTa' => $metadata->description[0],
            'GVHD' => $metadata->instructor[0],
            'SoSV' => $metadata->max_students[0],
            'TaiLieuThamKhao' => $metadata->references[0],
            'NgayBatDau' => DateTime::createFromFormat('d/m/Y', $metadata->start_date[0]),
            'NgayKetThuc' => DateTime::createFromFormat('d/m/Y', $metadata->end_date[0]),
            'NamHoc' => $metadata->schoolyear[0],
            'HocKy' => $metadata->semester[0],
            'Lop' => $metadata->class[0],
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
            $data = array(
                'ID' => $post_id,
                'post_title' => $title,
            );
            wp_update_post($data);

            // Update metadata post
            update_post_meta($post_id, 'description', $metadata->description);
            update_post_meta($post_id, 'instructor', $metadata->instructor);
            update_post_meta($post_id, 'max_students', $metadata->max_students);
            update_post_meta($post_id, 'references', $metadata->references);
            update_post_meta($post_id, 'start_date', $metadata->start_date);
            update_post_meta($post_id, 'end_date', $metadata->end_date);
            update_post_meta($post_id, 'schoolyear', $metadata->schoolyear);
            update_post_meta($post_id, 'semester', $metadata->semester);
            update_post_meta($post_id, 'class', $metadata->class);
        } catch (Exception $e) {
            return false;
        }
        return true;
    }
}

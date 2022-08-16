<?php

/**
 * Class DoAnInfo
 * 
 * @version  1.0.0
 * @package CustomUserRegistration
 */

require_once 'Constants.php';
require_once UR_PLUGIN_INCLUDES_DIR . '/utils.php';

class ur_Info
{
    /**
     * Add an instructor to meta data of first admin
     */
    public static function add_instructor(string $name)
    {
        $id = get_all_administrators()[0]->ID;
        if (!is_null($name) && is_string($name)) {
            $instructors = get_user_meta($id, UR_INSTRUCTORS_META_KEY, true);
            if ($instructors == null) // Nếu không tìm thấy
                $instructors = array();
            array_push($instructors, $name);
            $instructors = array_unique_incasesensitive($instructors); // Xóa trùng lặp
            sort_incasesensitive_lastname($instructors); // Sắp xếp

            update_user_meta($id, UR_INSTRUCTORS_META_KEY, $instructors);
            return true;
        }
        return false;
    }

    /**
     * Add a class to meta data of first admin
     */
    public static function add_class(string $class)
    {
        $id = get_all_administrators()[0]->ID;
        if (!is_null($class) && is_string($class)) {
            $classes = get_user_meta($id, UR_CLASSES_META_KEY, true);
            if ($classes == null)
                $classes = array();
            array_push($classes, $class);
            $classes = array_unique_incasesensitive($classes);
            sort_incasesensitive($classes);

            update_user_meta($id, UR_CLASSES_META_KEY, $classes);
            return true;
        }
        return false;
    }

    /**
     * Delete an instructor by index
     */
    public static function delete_instructor(int $index)
    {
        $id = get_all_administrators()[0]->ID;
        if (!is_null($index)) {
            $instructors = get_user_meta($id, UR_INSTRUCTORS_META_KEY, true);
            if (!is_null($instructors)) {
                unset($instructors[$index]);
                $instructors = array_values($instructors);
                update_user_meta($id, UR_INSTRUCTORS_META_KEY, $instructors);
                return true;
            }
        }
        return false;
    }

    /**
     * Delete a class by index
     */
    public static function delete_class(int $index)
    {
        $id = get_all_administrators()[0]->ID;
        if (!is_null($index)) {
            $classes = get_user_meta($id, UR_CLASSES_META_KEY, true);
            if (!is_null($classes)) {
                unset($classes[$index]);
                $classes = array_values($classes); // Reset indexes
                update_user_meta($id, UR_CLASSES_META_KEY, $classes);
                return true;
            }
        }
        return false;
    }

    /**
     * Get all instructors in metadata
     */
    public static function get_all_instructors()
    {
        $id = get_all_administrators()[0]->ID;
        $result = get_user_meta($id, UR_INSTRUCTORS_META_KEY, true);
        if (!is_null($result) && is_array($result)) {
            return $result;
        }
        return array();
    }

    /**
     * Get all classes in metadata
     */
    public static function get_all_classes()
    {
        $id = get_all_administrators()[0]->ID;
        $result = get_user_meta($id, UR_CLASSES_META_KEY, true);
        if (!is_null($result) && is_array($result)) {
            return $result;
        }
        return array();
    }
}

<?php

/**
 * Admin view: Kết quả đăng ký đồ án
 * 
 * @version  1.0.0
 * @package CustomUserRegistration
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

?>

<div class="wrap">
    <h1 class="wp-heading-inline">Danh sách đăng ký đồ án</h1>

    <form method="POST" action="" enctype="multipart/form-data">
        Tìm kiếm theo:
        <fieldset class="inline-edit-col-left" id="edit-instructor">
            <div class="inline-edit-col">
                <div class="inline-edit-group wp-clearfix">
                    <label class="inline-edit-instructor alignleft">
                        <span class="title">Giảng viên hướng dẫn:</span>
                        <input type="text" name="instructor" class="inline-edit-instructor-input" value="">
                    </label>
                </div>
            </div>
        </fieldset>
        <fieldset class="inline-edit-col-right" id="edit-instructor">
            <div class="inline-edit-col">
                <div class="inline-edit-group wp-clearfix">
                    <label class="inline-edit-instructor alignleft">
                        <span class="title">Giảng viên hướng dẫn:</span>
                        <input type="text" name="instructor" class="inline-edit-instructor-input" value="">
                    </label>
                </div>
            </div>
        </fieldset>
    </form>
</div>
<?php
/**
 * Plugin Name: Module (8) - Form Comment Make a Post
 * Description: Tùy biến form comment thành giao diện Make a Post (Bootsnipp) khi user đã đăng nhập.
 * Version: 1.0.0
 * Author: Tran Cao Trong
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// -----------------------------------------------------------------------------
// MODULE (8) - TÙY BIẾN FORM COMMENT KHI ĐÃ ĐĂNG NHẬP (MAKE A POST - BOOTSNIPP)
// -----------------------------------------------------------------------------
add_filter( 'comment_form_defaults', 'tdc_custom_comment_form_defaults', 9999 );
add_filter( 'astra_comment_form_default_markup', 'tdc_custom_comment_form_defaults', 9999 );
function tdc_custom_comment_form_defaults( $defaults ) {
    if ( is_user_logged_in() ) {
        $defaults['title_reply']          = ''; // Ẩn "Leave a comment"
        $defaults['title_reply_before']   = '<span style="display:none">';
        $defaults['title_reply_after']    = '</span>';
        $defaults['title_reply_to']       = '';
        $defaults['logged_in_as']         = ''; // Ẩn "Logged in as..."
        $defaults['comment_notes_before'] = '';
        $defaults['comment_notes_after']  = '';
        $defaults['class_form']          .= ' tdc-make-post-form';
        $defaults['class_submit']         = 'tdc-make-post-share-btn';
        $defaults['label_submit']         = 'share';
        
        // Tùy biến khung textarea dạng "Make a Post"
        $defaults['comment_field'] = '
        <div class="tdc-make-a-post-card">
            <div class="tdc-make-a-post-tab">Make a Post</div>
            <div class="tdc-make-a-post-body">
                <textarea id="comment" name="comment" class="tdc-make-a-post-textarea" placeholder="What are you thinking..." required="required"></textarea>
            </div>
        </div>';
        
        // Chỉnh sửa wrapper nút submit thành căn phải
        $defaults['submit_field'] = '<div class="tdc-make-a-post-footer">%1$s %2$s</div>';
    }
    return $defaults;
}

add_filter( 'comment_form_field_comment', function( $field ) {
    if ( is_user_logged_in() ) {
        return '
        <div class="tdc-make-a-post-card">
            <div class="tdc-make-a-post-tab">Make a Post</div>
            <div class="tdc-make-a-post-body">
                <textarea id="comment" name="comment" class="tdc-make-a-post-textarea" placeholder="What are you thinking..." required="required"></textarea>
            </div>
        </div>';
    }
    return $field;
}, 9999 );

add_filter( 'comment_form_submit_button', function( $submit_button, $args ) {
    if ( is_user_logged_in() ) {
        return '<button name="submit" type="submit" id="submit" class="tdc-make-post-share-btn">share</button>';
    }
    return $submit_button;
}, 9999, 2 );

// CSS Styling riêng cho Module 8
add_action( 'wp_head', function() {
    ?>
    <style id="tdc-module-8-css">
    .tdc-make-post-form {
        margin-top: 30px;
    }
    .tdc-make-a-post-card {
        position: relative;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        padding: 0;
        margin-top: 35px;
        margin-bottom: 10px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
    }
    .tdc-make-a-post-tab {
        position: absolute;
        top: -33px;
        left: 16px;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-bottom: 1px solid #ffffff;
        padding: 6px 20px;
        border-radius: 6px 6px 0 0;
        font-weight: 600;
        color: #4a5568;
        font-size: 15px;
        z-index: 2;
    }
    .tdc-make-a-post-body {
        padding: 16px;
    }
    .tdc-make-a-post-textarea {
        width: 100% !important;
        border: 1px solid #d1d5db !important;
        border-radius: 6px !important;
        padding: 12px 14px !important;
        font-size: 15px !important;
        color: #374151 !important;
        min-height: 95px !important;
        background: #ffffff !important;
        box-sizing: border-box !important;
        outline: none !important;
        resize: vertical !important;
        transition: border-color 0.2s;
    }
    .tdc-make-a-post-textarea:focus {
        border-color: #007bff !important;
        box-shadow: 0 0 0 2px rgba(0,123,255,0.15) !important;
    }
    .tdc-make-a-post-footer {
        display: flex;
        justify-content: flex-end;
        margin-top: 8px;
        padding: 0 4px;
    }
    .tdc-make-post-share-btn {
        background-color: #007bff !important;
        color: #ffffff !important;
        border: none !important;
        padding: 7px 26px !important;
        border-radius: 5px !important;
        font-size: 14px !important;
        font-weight: 600 !important;
        cursor: pointer !important;
        text-transform: lowercase !important;
        transition: background-color 0.2s;
        display: inline-block;
    }
    .tdc-make-post-share-btn:hover {
        background-color: #0056b3 !important;
    }
    </style>
    <?php
}, 99 );

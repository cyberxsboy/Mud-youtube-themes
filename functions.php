<?php
/**
 * Mud YouTube Themes Functions
 * 
 * @package Mud_YouTube_Themes
 * @version 1.0.0
 * @author 泥人传说
 * @author_uri https://nirenchuanshuo.com
 * @since 1.0.0
 * 
 * This file contains the main functionality for the Mud YouTube Themes.
 * It includes custom post types, user management, video handling, and theme setup.
 * 
 * Copyright (C) 2025 泥人传说
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Theme Setup
 */
function mudtube_setup() {
    // Add theme support
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo');
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
    ));
    
    // Add image sizes
    add_image_size('video-thumbnail', 320, 180, true);
    add_image_size('video-large', 1280, 720, true);
    
    // Register navigation menus
    register_nav_menus(array(
        'primary' => 'Primary Menu',
        'footer' => 'Footer Menu',
    ));
}
add_action('after_setup_theme', 'mudtube_setup');

/**
 * Enqueue scripts and styles
 */
function mudtube_scripts() {
    wp_enqueue_style('mudtube-style', get_stylesheet_uri(), array(), '1.0.0');
    wp_enqueue_script('mudtube-script', get_template_directory_uri() . '/js/main.js', array('jquery'), '1.0.0', true);
    
    // Localize script for AJAX
    wp_localize_script('mudtube-script', 'mudtube_ajax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('mudtube_nonce'),
    ));
}
add_action('wp_enqueue_scripts', 'mudtube_scripts');

/**
 * Register Custom Post Type: Video
 */
function mudtube_register_video_post_type() {
    $labels = array(
        'name' => '视频',
        'singular_name' => '视频',
        'menu_name' => '视频',
        'add_new' => '添加新视频',
        'add_new_item' => '添加新视频',
        'edit_item' => '编辑视频',
        'new_item' => '新视频',
        'view_item' => '查看视频',
        'search_items' => '搜索视频',
        'not_found' => '未找到视频',
        'not_found_in_trash' => '回收站中未找到视频',
    );
    
    $args = array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'video'),
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => 5,
        'menu_icon' => 'dashicons-video-alt3',
        'supports' => array('title', 'editor', 'author', 'thumbnail', 'comments'),
        'show_in_rest' => true,
    );
    
    register_post_type('video', $args);
}
add_action('init', 'mudtube_register_video_post_type');

/**
 * Add custom meta boxes for videos
 */
function mudtube_add_video_meta_boxes() {
    add_meta_box(
        'video_details',
        '视频详情',
        'mudtube_video_details_callback',
        'video',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'mudtube_add_video_meta_boxes');

/**
 * Video details meta box callback
 */
function mudtube_video_details_callback($post) {
    wp_nonce_field('mudtube_save_video_details', 'mudtube_video_details_nonce');
    
    $video_file = get_post_meta($post->ID, '_video_file', true);
    $video_thumbnail = get_post_meta($post->ID, '_video_thumbnail', true);
    $video_duration = get_post_meta($post->ID, '_video_duration', true);
    $view_count = get_post_meta($post->ID, '_view_count', true);
    
    ?>
    <table class="form-table">
        <tr>
            <th><label for="video_file">视频文件URL</label></th>
            <td><input type="url" id="video_file" name="video_file" value="<?php echo esc_attr($video_file); ?>" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="video_thumbnail">视频缩略图URL</label></th>
            <td><input type="url" id="video_thumbnail" name="video_thumbnail" value="<?php echo esc_attr($video_thumbnail); ?>" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="video_duration">视频时长</label></th>
            <td><input type="text" id="video_duration" name="video_duration" value="<?php echo esc_attr($video_duration); ?>" placeholder="例如: 10:30" /></td>
        </tr>
        <tr>
            <th><label for="view_count">观看次数</label></th>
            <td><input type="number" id="view_count" name="view_count" value="<?php echo esc_attr($view_count); ?>" min="0" /></td>
        </tr>
    </table>
    <?php
}

/**
 * Save video details
 */
function mudtube_save_video_details($post_id) {
    if (!isset($_POST['mudtube_video_details_nonce']) || 
        !wp_verify_nonce($_POST['mudtube_video_details_nonce'], 'mudtube_save_video_details')) {
        return;
    }
    
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    $fields = array('video_file', 'video_thumbnail', 'video_duration', 'view_count');
    
    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, '_' . $field, sanitize_text_field($_POST[$field]));
        }
    }
}
add_action('save_post', 'mudtube_save_video_details');

/**
 * Custom login/register functionality
 */
function mudtube_handle_registration() {
    if (isset($_POST['mudtube_register'])) {
        $username = sanitize_user($_POST['username']);
        $email = sanitize_email($_POST['email']);
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];
        
        $errors = array();
        
        // Validation
        if (empty($username)) {
            $errors[] = '用户名不能为空';
        } elseif (username_exists($username)) {
            $errors[] = '用户名已存在';
        }
        
        if (empty($email)) {
            $errors[] = '邮箱不能为空';
        } elseif (!is_email($email)) {
            $errors[] = '邮箱格式不正确';
        } elseif (email_exists($email)) {
            $errors[] = '邮箱已被注册';
        }
        
        if (empty($password)) {
            $errors[] = '密码不能为空';
        } elseif (strlen($password) < 6) {
            $errors[] = '密码至少6位';
        }
        
        if ($password !== $confirm_password) {
            $errors[] = '两次密码输入不一致';
        }
        
        if (empty($errors)) {
            $user_id = wp_create_user($username, $password, $email);
            
            if (!is_wp_error($user_id)) {
                wp_set_current_user($user_id);
                wp_set_auth_cookie($user_id);
                wp_redirect(home_url());
                exit;
            } else {
                $errors[] = $user_id->get_error_message();
            }
        }
        
        // Store errors in session or transient
        set_transient('mudtube_registration_errors', $errors, 300);
    }
}
add_action('init', 'mudtube_handle_registration');

/**
 * Handle video upload
 */
function mudtube_handle_video_upload() {
    if (!is_user_logged_in()) {
        wp_die('请先登录');
    }
    
    if (isset($_POST['mudtube_upload_video'])) {
        $title = sanitize_text_field($_POST['video_title']);
        $description = wp_kses_post($_POST['video_description']);
        $category = sanitize_text_field($_POST['video_category']);
        
        if (empty($title)) {
            wp_die('视频标题不能为空');
        }
        
        // Handle file upload
        if (!empty($_FILES['video_file']['name'])) {
            $uploaded_file = wp_handle_upload($_FILES['video_file'], array('test_form' => false));
            
            if ($uploaded_file && !isset($uploaded_file['error'])) {
                $video_url = $uploaded_file['url'];
                
                // Create video post
                $post_data = array(
                    'post_title' => $title,
                    'post_content' => $description,
                    'post_status' => 'publish',
                    'post_type' => 'video',
                    'post_author' => get_current_user_id(),
                );
                
                if (!empty($category)) {
                    $post_data['post_category'] = array($category);
                }
                
                $post_id = wp_insert_post($post_data);
                
                if ($post_id) {
                    update_post_meta($post_id, '_video_file', $video_url);
                    update_post_meta($post_id, '_view_count', 0);
                    
                    // Handle thumbnail upload
                    if (!empty($_FILES['video_thumbnail']['name'])) {
                        $thumbnail_file = wp_handle_upload($_FILES['video_thumbnail'], array('test_form' => false));
                        if ($thumbnail_file && !isset($thumbnail_file['error'])) {
                            update_post_meta($post_id, '_video_thumbnail', $thumbnail_file['url']);
                        }
                    }
                    
                    wp_redirect(get_permalink($post_id));
                    exit;
                }
            }
        }
    }
}
add_action('init', 'mudtube_handle_video_upload');

/**
 * AJAX handler for video likes
 */
function mudtube_handle_video_like() {
    check_ajax_referer('mudtube_nonce', 'nonce');
    
    if (!is_user_logged_in()) {
        wp_die('请先登录');
    }
    
    $video_id = intval($_POST['video_id']);
    $user_id = get_current_user_id();
    
    $liked_videos = get_user_meta($user_id, '_liked_videos', true);
    if (!is_array($liked_videos)) {
        $liked_videos = array();
    }
    
    $like_count = get_post_meta($video_id, '_like_count', true);
    if (!$like_count) {
        $like_count = 0;
    }
    
    if (in_array($video_id, $liked_videos)) {
        // Unlike
        $liked_videos = array_diff($liked_videos, array($video_id));
        $like_count = max(0, $like_count - 1);
        $liked = false;
    } else {
        // Like
        $liked_videos[] = $video_id;
        $like_count++;
        $liked = true;
    }
    
    update_user_meta($user_id, '_liked_videos', $liked_videos);
    update_post_meta($video_id, '_like_count', $like_count);
    
    wp_send_json_success(array(
        'liked' => $liked,
        'like_count' => $like_count
    ));
}
add_action('wp_ajax_mudtube_like_video', 'mudtube_handle_video_like');

/**
 * AJAX handler for subscriptions
 */
function mudtube_handle_subscription() {
    check_ajax_referer('mudtube_nonce', 'nonce');
    
    if (!is_user_logged_in()) {
        wp_die('请先登录');
    }
    
    $channel_id = intval($_POST['channel_id']);
    $user_id = get_current_user_id();
    
    if ($channel_id === $user_id) {
        wp_die('不能订阅自己的频道');
    }
    
    $subscriptions = get_user_meta($user_id, '_subscriptions', true);
    if (!is_array($subscriptions)) {
        $subscriptions = array();
    }
    
    $subscriber_count = get_user_meta($channel_id, '_subscriber_count', true);
    if (!$subscriber_count) {
        $subscriber_count = 0;
    }
    
    if (in_array($channel_id, $subscriptions)) {
        // Unsubscribe
        $subscriptions = array_diff($subscriptions, array($channel_id));
        $subscriber_count = max(0, $subscriber_count - 1);
        $subscribed = false;
    } else {
        // Subscribe
        $subscriptions[] = $channel_id;
        $subscriber_count++;
        $subscribed = true;
    }
    
    update_user_meta($user_id, '_subscriptions', $subscriptions);
    update_user_meta($channel_id, '_subscriber_count', $subscriber_count);
    
    wp_send_json_success(array(
        'subscribed' => $subscribed,
        'subscriber_count' => $subscriber_count
    ));
}
add_action('wp_ajax_mudtube_subscribe', 'mudtube_handle_subscription');

/**
 * Increment video view count
 */
function mudtube_increment_view_count() {
    if (is_singular('video')) {
        $post_id = get_the_ID();
        $view_count = get_post_meta($post_id, '_view_count', true);
        if (!$view_count) {
            $view_count = 0;
        }
        $view_count++;
        update_post_meta($post_id, '_view_count', $view_count);
    }
}
add_action('wp_head', 'mudtube_increment_view_count');

/**
 * Custom search functionality
 */
function mudtube_search_filter($query) {
    if (!is_admin() && $query->is_main_query()) {
        if ($query->is_search()) {
            $query->set('post_type', array('video'));
        }
    }
}
add_action('pre_get_posts', 'mudtube_search_filter');

/**
 * Add custom user fields
 */
function mudtube_add_user_fields($user) {
    ?>
    <h3>频道信息</h3>
    <table class="form-table">
        <tr>
            <th><label for="channel_description">频道描述</label></th>
            <td>
                <textarea name="channel_description" id="channel_description" rows="5" cols="30"><?php echo esc_attr(get_user_meta($user->ID, 'channel_description', true)); ?></textarea>
                <br><span class="description">请输入您的频道描述</span>
            </td>
        </tr>
        <tr>
            <th><label for="channel_banner">频道横幅URL</label></th>
            <td>
                <input type="url" name="channel_banner" id="channel_banner" value="<?php echo esc_attr(get_user_meta($user->ID, 'channel_banner', true)); ?>" class="regular-text" />
                <br><span class="description">频道页面的横幅图片URL</span>
            </td>
        </tr>
    </table>
    <?php
}
add_action('show_user_profile', 'mudtube_add_user_fields');
add_action('edit_user_profile', 'mudtube_add_user_fields');

/**
 * Save custom user fields
 */
function mudtube_save_user_fields($user_id) {
    if (!current_user_can('edit_user', $user_id)) {
        return false;
    }
    
    update_user_meta($user_id, 'channel_description', sanitize_textarea_field($_POST['channel_description']));
    update_user_meta($user_id, 'channel_banner', esc_url_raw($_POST['channel_banner']));
}
add_action('personal_options_update', 'mudtube_save_user_fields');
add_action('edit_user_profile_update', 'mudtube_save_user_fields');

/**
 * Custom comment form for videos
 */
function mudtube_comment_form_defaults($defaults) {
    if (get_post_type() === 'video') {
        $defaults['title_reply'] = '添加评论';
        $defaults['label_submit'] = '评论';
        $defaults['comment_field'] = '<p class="comment-form-comment"><textarea id="comment" name="comment" cols="45" rows="3" maxlength="65525" required="required" placeholder="添加公开评论..."></textarea></p>';
    }
    return $defaults;
}
add_filter('comment_form_defaults', 'mudtube_comment_form_defaults');

/**
 * Format numbers (views, likes, etc.)
 */
function mudtube_format_number($number) {
    if ($number >= 1000000) {
        return round($number / 1000000, 1) . 'M';
    } elseif ($number >= 1000) {
        return round($number / 1000, 1) . 'K';
    }
    return number_format($number);
}

/**
 * Get video duration in seconds
 */
function mudtube_get_video_duration($file_path) {
    // This would require FFmpeg or similar
    // For now, return a placeholder
    return '0:00';
}

/**
 * Security enhancements
 */
// Remove WordPress version info
remove_action('wp_head', 'wp_generator');

// Disable file editing
define('DISALLOW_FILE_EDIT', true);

// Limit login attempts (basic implementation)
function mudtube_limit_login_attempts() {
    $ip = $_SERVER['REMOTE_ADDR'];
    $attempts = get_transient('mudtube_login_attempts_' . $ip);
    
    if ($attempts && $attempts >= 5) {
        wp_die('登录尝试次数过多，请稍后再试。');
    }
}
add_action('wp_login_failed', function() {
    $ip = $_SERVER['REMOTE_ADDR'];
    $attempts = get_transient('mudtube_login_attempts_' . $ip);
    $attempts = $attempts ? $attempts + 1 : 1;
    set_transient('mudtube_login_attempts_' . $ip, $attempts, 900); // 15 minutes
});

/**
 * Compatibility with older PHP versions
 */
if (!function_exists('wp_body_open')) {
    function wp_body_open() {
        do_action('wp_body_open');
    }
}

/**
 * Theme customizer
 */
function mudtube_customize_register($wp_customize) {
    // Site colors
    $wp_customize->add_section('mudtube_colors', array(
        'title' => '主题颜色',
        'priority' => 30,
    ));
    
    $wp_customize->add_setting('accent_color', array(
        'default' => '#3ea6ff',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'accent_color', array(
        'label' => '强调色',
        'section' => 'mudtube_colors',
    )));
}
add_action('customize_register', 'mudtube_customize_register');

/**
 * Add admin styles
 */
function mudtube_admin_styles() {
    echo '<style>
        .post-type-video .wp-list-table .column-title { width: 30%; }
        .post-type-video .wp-list-table .column-author { width: 15%; }
        .post-type-video .wp-list-table .column-date { width: 15%; }
    </style>';
}
add_action('admin_head', 'mudtube_admin_styles');

/**
 * Custom columns for video post type
 */
function mudtube_video_columns($columns) {
    $columns['video_thumbnail'] = '缩略图';
    $columns['view_count'] = '观看次数';
    $columns['like_count'] = '点赞数';
    return $columns;
}
add_filter('manage_video_posts_columns', 'mudtube_video_columns');

function mudtube_video_column_content($column, $post_id) {
    switch ($column) {
        case 'video_thumbnail':
            $thumbnail = get_post_meta($post_id, '_video_thumbnail', true);
            if ($thumbnail) {
                echo '<img src="' . esc_url($thumbnail) . '" style="width: 60px; height: auto;">';
            } else {
                echo '无缩略图';
            }
            break;
        case 'view_count':
            $views = get_post_meta($post_id, '_view_count', true);
            echo $views ? number_format($views) : '0';
            break;
        case 'like_count':
            $likes = get_post_meta($post_id, '_like_count', true);
            echo $likes ? number_format($likes) : '0';
            break;
    }
}
add_action('manage_video_posts_custom_column', 'mudtube_video_column_content', 10, 2);

/**
 * Include installation script
 */
require_once get_template_directory() . '/install.php';

?> 
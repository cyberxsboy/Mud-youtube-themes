<?php
/**
 * Mud YouTube Themes Installation Script
 * 
 * This script helps set up the theme by creating necessary pages
 * and configuring basic settings.
 * 
 * @package Mud_YouTube_Themes
 * @version 1.0.0
 * @author 泥人传说
 * @author_uri https://nirenchuanshuo.com
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Run theme installation
 */
function mudtube_install_theme() {
    // Create necessary pages
    mudtube_create_pages();
    
    // Set default options
    mudtube_set_default_options();
    
    // Flush rewrite rules
    flush_rewrite_rules();
    
    // Set installation flag
    update_option('mudtube_theme_installed', true);
}

/**
 * Create necessary pages
 */
function mudtube_create_pages() {
    $pages = array(
        'register' => array(
            'title' => '注册',
            'content' => '<!-- 用户注册页面 -->',
            'template' => 'page-register.php'
        ),
        'upload' => array(
            'title' => '上传视频',
            'content' => '<!-- 视频上传页面 -->',
            'template' => 'page-upload.php'
        ),
        'trending' => array(
            'title' => '热门视频',
            'content' => '<!-- 热门视频页面 -->'
        ),
        'subscriptions' => array(
            'title' => '订阅内容',
            'content' => '<!-- 订阅内容页面 -->'
        ),
        'library' => array(
            'title' => '媒体库',
            'content' => '<!-- 媒体库页面 -->'
        ),
        'history' => array(
            'title' => '历史记录',
            'content' => '<!-- 历史记录页面 -->'
        ),
        'my-videos' => array(
            'title' => '我的视频',
            'content' => '<!-- 我的视频页面 -->'
        ),
        'liked' => array(
            'title' => '喜欢的视频',
            'content' => '<!-- 喜欢的视频页面 -->'
        )
    );
    
    foreach ($pages as $slug => $page_data) {
        // Check if page already exists
        $existing_page = get_page_by_path($slug);
        
        if (!$existing_page) {
            $page_id = wp_insert_post(array(
                'post_title' => $page_data['title'],
                'post_content' => $page_data['content'],
                'post_status' => 'publish',
                'post_type' => 'page',
                'post_name' => $slug
            ));
            
            // Set page template if specified
            if (isset($page_data['template']) && $page_id) {
                update_post_meta($page_id, '_wp_page_template', $page_data['template']);
            }
        }
    }
}

/**
 * Set default theme options
 */
function mudtube_set_default_options() {
    // Set default permalink structure
    if (get_option('permalink_structure') === '') {
        update_option('permalink_structure', '/%postname%/');
    }
    
    // Set default timezone
    if (get_option('timezone_string') === '') {
        update_option('timezone_string', 'Asia/Shanghai');
    }
    
    // Set default date format
    update_option('date_format', 'Y年n月j日');
    update_option('time_format', 'H:i');
    
    // Set default media settings
    update_option('thumbnail_size_w', 150);
    update_option('thumbnail_size_h', 150);
    update_option('medium_size_w', 320);
    update_option('medium_size_h', 180);
    update_option('large_size_w', 1280);
    update_option('large_size_h', 720);
    
    // Enable user registration
    update_option('users_can_register', 1);
    update_option('default_role', 'subscriber');
    
    // Set comments settings
    update_option('default_comment_status', 'open');
    update_option('require_name_email', 1);
    update_option('comment_registration', 0);
    
    // Set reading settings
    update_option('show_on_front', 'posts');
    update_option('posts_per_page', 24);
    
    // Set discussion settings
    update_option('thread_comments', 1);
    update_option('thread_comments_depth', 3);
    update_option('page_comments', 1);
    update_option('comments_per_page', 20);
}

/**
 * Create sample content
 */
function mudtube_create_sample_content() {
    // Create sample video categories
    $categories = array('音乐', '游戏', '教育', '娱乐', '新闻', '体育', '科技', '生活');
    
    foreach ($categories as $category) {
        if (!term_exists($category, 'category')) {
            wp_insert_term($category, 'category');
        }
    }
    
            // Create sample video post
        $sample_video = array(
            'post_title' => '欢迎使用 Mud YouTube Themes 主题',
            'post_content' => '这是一个示例视频，展示了 Mud YouTube Themes 主题的功能。您可以删除这个视频并开始上传您自己的内容。',
        'post_status' => 'publish',
        'post_type' => 'video',
        'post_author' => 1
    );
    
    $video_id = wp_insert_post($sample_video);
    
    if ($video_id) {
        // Add sample meta data
        update_post_meta($video_id, '_video_file', '');
        update_post_meta($video_id, '_video_thumbnail', '');
        update_post_meta($video_id, '_video_duration', '2:30');
        update_post_meta($video_id, '_view_count', 100);
        update_post_meta($video_id, '_like_count', 10);
    }
}

/**
 * Check if theme installation is needed
 */
function mudtube_check_installation() {
    if (!get_option('mudtube_theme_installed')) {
        add_action('admin_notices', 'mudtube_installation_notice');
    }
}

/**
 * Display installation notice
 */
function mudtube_installation_notice() {
    if (current_user_can('manage_options')) {
        ?>
        <div class="notice notice-info is-dismissible">
            <p>
                <strong>Mud YouTube Themes 主题</strong>：检测到这是首次使用，是否要运行自动安装程序来创建必要的页面和设置？
                <a href="<?php echo admin_url('admin.php?page=mudtube-install'); ?>" class="button button-primary">立即安装</a>
                <a href="<?php echo add_query_arg('mudtube_skip_install', '1'); ?>" class="button">跳过</a>
            </p>
        </div>
        <?php
    }
}

/**
 * Add admin menu for installation
 */
function mudtube_add_admin_menu() {
    add_theme_page(
        'MudTube 安装',
        'MudTube 安装',
        'manage_options',
        'mudtube-install',
        'mudtube_installation_page'
    );
}

/**
 * Installation page
 */
function mudtube_installation_page() {
    if (isset($_POST['install_theme'])) {
        mudtube_install_theme();
        echo '<div class="notice notice-success"><p>主题安装完成！</p></div>';
    }
    
    if (isset($_POST['create_sample_content'])) {
        mudtube_create_sample_content();
        echo '<div class="notice notice-success"><p>示例内容创建完成！</p></div>';
    }
    ?>
    <div class="wrap">
        <h1>MudTube 主题安装</h1>
        
        <div class="card">
            <h2>基础安装</h2>
            <p>创建必要的页面和配置基本设置。</p>
            <form method="post">
                <input type="hidden" name="install_theme" value="1">
                <?php wp_nonce_field('mudtube_install'); ?>
                <p>
                    <input type="submit" class="button button-primary" value="安装主题">
                </p>
            </form>
        </div>
        
        <div class="card">
            <h2>示例内容</h2>
            <p>创建示例视频分类和内容（可选）。</p>
            <form method="post">
                <input type="hidden" name="create_sample_content" value="1">
                <?php wp_nonce_field('mudtube_sample'); ?>
                <p>
                    <input type="submit" class="button" value="创建示例内容">
                </p>
            </form>
        </div>
        
        <div class="card">
            <h2>手动配置</h2>
            <p>如果您喜欢手动配置，请按照以下步骤操作：</p>
            <ol>
                <li>创建"注册"页面，选择模板"Register Page"</li>
                <li>创建"上传"页面，选择模板"Upload Page"</li>
                <li>进入 设置 > 固定链接，选择"文章名"</li>
                <li>进入 设置 > 常规，启用"任何人都可以注册"</li>
                <li>进入 设置 > 讨论，配置评论设置</li>
            </ol>
        </div>
        
        <div class="card">
            <h2>下一步</h2>
            <p>安装完成后，您可以：</p>
            <ul>
                <li><a href="<?php echo admin_url('customize.php'); ?>">自定义主题外观</a></li>
                <li><a href="<?php echo admin_url('edit.php?post_type=video'); ?>">管理视频内容</a></li>
                <li><a href="<?php echo admin_url('users.php'); ?>">管理用户</a></li>
                <li><a href="<?php echo home_url(); ?>">查看网站前台</a></li>
            </ul>
        </div>
    </div>
    
    <style>
    .card {
        background: #fff;
        border: 1px solid #ccd0d4;
        border-radius: 4px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 1px 1px rgba(0,0,0,.04);
    }
    .card h2 {
        margin-top: 0;
    }
    </style>
    <?php
}

/**
 * Handle skip installation
 */
function mudtube_handle_skip_install() {
    if (isset($_GET['mudtube_skip_install'])) {
        update_option('mudtube_theme_installed', true);
        wp_redirect(admin_url());
        exit;
    }
}

// Hook into WordPress
add_action('after_setup_theme', 'mudtube_check_installation');
add_action('admin_menu', 'mudtube_add_admin_menu');
add_action('admin_init', 'mudtube_handle_skip_install');

?> 
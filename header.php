<?php
/**
 * The header template file
 * 
 * This template displays the site header including navigation,
 * search bar, user menu, and sidebar navigation.
 * 
 * @package Mud_YouTube_Themes
 * @version 1.0.0
 * @author 泥人传说
 * @author_uri https://nirenchuanshuo.com
 * @since 1.0.0
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="site-header">
    <div class="header-left">
        <button class="menu-toggle" id="menu-toggle">
            <i class="fas fa-bars"></i>
        </button>
        <a href="<?php echo home_url(); ?>" class="site-logo">
            <?php if (has_custom_logo()): ?>
                <?php the_custom_logo(); ?>
            <?php else: ?>
                <i class="fab fa-youtube" style="color: #ff0000; font-size: 24px; margin-right: 8px;"></i>
                <span class="site-title"><?php bloginfo('name'); ?></span>
            <?php endif; ?>
        </a>
    </div>
    
    <div class="header-center">
        <form class="search-form" role="search" method="get" action="<?php echo home_url('/'); ?>">
            <input type="search" class="search-input" placeholder="搜索" value="<?php echo get_search_query(); ?>" name="s">
            <button type="submit" class="search-button">
                <i class="fas fa-search"></i>
            </button>
        </form>
    </div>
    
    <div class="header-right">
        <?php if (is_user_logged_in()): ?>
            <a href="<?php echo home_url('/upload'); ?>" class="upload-button">
                <i class="fas fa-plus"></i>
                创建
            </a>
            <button class="header-button" title="通知">
                <i class="fas fa-bell"></i>
            </button>
            <?php
            $current_user = wp_get_current_user();
            $user_avatar = get_avatar_url($current_user->ID, array('size' => 32));
            ?>
            <div class="user-menu">
                <img src="<?php echo esc_url($user_avatar); ?>" alt="<?php echo esc_attr($current_user->display_name); ?>" class="user-avatar" id="user-avatar">
                <div class="user-dropdown" id="user-dropdown">
                    <div class="dropdown-header">
                        <img src="<?php echo esc_url($user_avatar); ?>" alt="<?php echo esc_attr($current_user->display_name); ?>" class="dropdown-avatar">
                        <div class="dropdown-info">
                            <div class="dropdown-name"><?php echo esc_html($current_user->display_name); ?></div>
                            <div class="dropdown-email"><?php echo esc_html($current_user->user_email); ?></div>
                        </div>
                    </div>
                    <div class="dropdown-divider"></div>
                    <a href="<?php echo get_author_posts_url($current_user->ID); ?>" class="dropdown-item">
                        <i class="fas fa-user"></i>
                        我的频道
                    </a>
                    <a href="<?php echo home_url('/my-videos'); ?>" class="dropdown-item">
                        <i class="fas fa-video"></i>
                        我的视频
                    </a>
                    <a href="<?php echo wp_logout_url(home_url()); ?>" class="dropdown-item">
                        <i class="fas fa-sign-out-alt"></i>
                        退出登录
                    </a>
                </div>
            </div>
        <?php else: ?>
            <a href="<?php echo wp_login_url(); ?>" class="upload-button">
                <i class="fas fa-user"></i>
                登录
            </a>
        <?php endif; ?>
    </div>
</header>

<!-- Sidebar -->
<nav class="sidebar" id="sidebar">
    <div class="sidebar-section">
        <a href="<?php echo home_url(); ?>" class="sidebar-item">
            <i class="fas fa-home"></i>
            首页
        </a>
        <a href="<?php echo home_url('/trending'); ?>" class="sidebar-item">
            <i class="fas fa-fire"></i>
            热门
        </a>
        <a href="<?php echo home_url('/subscriptions'); ?>" class="sidebar-item">
            <i class="fas fa-play-circle"></i>
            订阅内容
        </a>
    </div>
    
    <div class="sidebar-section">
        <a href="<?php echo home_url('/library'); ?>" class="sidebar-item">
            <i class="fas fa-folder"></i>
            媒体库
        </a>
        <a href="<?php echo home_url('/history'); ?>" class="sidebar-item">
            <i class="fas fa-history"></i>
            历史记录
        </a>
        <?php if (is_user_logged_in()): ?>
            <a href="<?php echo home_url('/my-videos'); ?>" class="sidebar-item">
                <i class="fas fa-video"></i>
                我的视频
            </a>
            <a href="<?php echo home_url('/liked'); ?>" class="sidebar-item">
                <i class="fas fa-thumbs-up"></i>
                喜欢的视频
            </a>
        <?php endif; ?>
    </div>
    
    <div class="sidebar-section">
        <h3 class="sidebar-title">订阅</h3>
        <?php
        if (is_user_logged_in()) {
            // Get user's subscriptions
            $current_user_id = get_current_user_id();
            $subscriptions = get_user_meta($current_user_id, '_subscriptions', true);
            
            if ($subscriptions && is_array($subscriptions)) {
                foreach ($subscriptions as $user_id) {
                    $user = get_userdata($user_id);
                    if ($user) {
                        $avatar = get_avatar_url($user_id, array('size' => 24));
                        ?>
                        <a href="<?php echo get_author_posts_url($user_id); ?>" class="sidebar-item">
                            <img src="<?php echo esc_url($avatar); ?>" alt="<?php echo esc_attr($user->display_name); ?>" style="width: 24px; height: 24px; border-radius: 50%; margin-right: 24px;">
                            <?php echo esc_html($user->display_name); ?>
                        </a>
                        <?php
                    }
                }
            }
        }
        ?>
    </div>
    
    <div class="sidebar-section">
        <h3 class="sidebar-title">探索</h3>
        <a href="<?php echo home_url('/category/music'); ?>" class="sidebar-item">
            <i class="fas fa-music"></i>
            音乐
        </a>
        <a href="<?php echo home_url('/category/gaming'); ?>" class="sidebar-item">
            <i class="fas fa-gamepad"></i>
            游戏
        </a>
        <a href="<?php echo home_url('/category/news'); ?>" class="sidebar-item">
            <i class="fas fa-newspaper"></i>
            新闻
        </a>
        <a href="<?php echo home_url('/category/sports'); ?>" class="sidebar-item">
            <i class="fas fa-futbol"></i>
            体育
        </a>
    </div>
</nav>

<div class="sidebar-overlay" id="sidebar-overlay"></div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const menuToggle = document.getElementById('menu-toggle');
    const sidebar = document.getElementById('sidebar');
    const sidebarOverlay = document.getElementById('sidebar-overlay');
    const userAvatar = document.getElementById('user-avatar');
    const userDropdown = document.getElementById('user-dropdown');
    
    // Toggle sidebar
    if (menuToggle && sidebar) {
        menuToggle.addEventListener('click', function() {
            sidebar.classList.toggle('open');
            document.body.classList.toggle('sidebar-open');
        });
    }
    
    // Close sidebar when clicking overlay
    if (sidebarOverlay) {
        sidebarOverlay.addEventListener('click', function() {
            sidebar.classList.remove('open');
            document.body.classList.remove('sidebar-open');
        });
    }
    
    // Toggle user dropdown
    if (userAvatar && userDropdown) {
        userAvatar.addEventListener('click', function(e) {
            e.stopPropagation();
            userDropdown.classList.toggle('show');
        });
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function() {
            userDropdown.classList.remove('show');
        });
        
        userDropdown.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    }
});
</script>

<style>
.user-menu {
    position: relative;
}

.user-dropdown {
    position: absolute;
    top: 100%;
    right: 0;
    background-color: #212121;
    border: 1px solid #303030;
    border-radius: 8px;
    min-width: 300px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
    z-index: 1002;
    opacity: 0;
    visibility: hidden;
    transform: translateY(-10px);
    transition: all 0.2s ease;
}

.user-dropdown.show {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
}

.dropdown-header {
    display: flex;
    align-items: center;
    padding: 16px;
    gap: 12px;
}

.dropdown-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
}

.dropdown-name {
    font-weight: 500;
    color: #ffffff;
    font-size: 16px;
}

.dropdown-email {
    color: #aaaaaa;
    font-size: 14px;
}

.dropdown-divider {
    height: 1px;
    background-color: #303030;
    margin: 8px 0;
}

.dropdown-item {
    display: flex;
    align-items: center;
    padding: 12px 16px;
    color: #ffffff;
    text-decoration: none;
    font-size: 14px;
    gap: 16px;
}

.dropdown-item:hover {
    background-color: #303030;
}

.dropdown-item i {
    width: 20px;
    text-align: center;
}

.sidebar-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 998;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s ease;
}

.sidebar-open .sidebar-overlay {
    opacity: 1;
    visibility: visible;
}

.sidebar-title {
    padding: 8px 24px;
    font-size: 14px;
    font-weight: 500;
    color: #aaaaaa;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

@media (max-width: 768px) {
    .sidebar-overlay {
        display: block;
    }
}
</style> 
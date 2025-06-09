<?php
/**
 * 404 Error Page Template
 */

get_header(); ?>

<div class="main-content">
    <div class="error-404">
        <div class="error-content">
            <div class="error-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            
            <h1 class="error-title">404</h1>
            <h2 class="error-subtitle">页面未找到</h2>
            
            <p class="error-message">
                抱歉，您访问的页面不存在。可能是链接错误或页面已被删除。
            </p>
            
            <div class="error-actions">
                <a href="<?php echo home_url(); ?>" class="btn-primary">
                    <i class="fas fa-home"></i>
                    返回首页
                </a>
                
                <button onclick="history.back()" class="btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                    返回上页
                </button>
            </div>
            
            <div class="search-section">
                <h3>搜索视频</h3>
                <form class="search-form" role="search" method="get" action="<?php echo home_url('/'); ?>">
                    <div class="search-input-group">
                        <input type="search" class="search-input" placeholder="搜索您想要的视频..." name="s">
                        <button type="submit" class="search-button">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>
            
            <div class="popular-videos">
                <h3>热门视频</h3>
                <div class="video-grid">
                    <?php
                    $popular_videos = new WP_Query(array(
                        'post_type' => 'video',
                        'posts_per_page' => 6,
                        'meta_key' => '_view_count',
                        'orderby' => 'meta_value_num',
                        'order' => 'DESC'
                    ));
                    
                    if ($popular_videos->have_posts()):
                        while ($popular_videos->have_posts()): $popular_videos->the_post();
                            $video_thumbnail = get_post_meta(get_the_ID(), '_video_thumbnail', true);
                            $view_count = get_post_meta(get_the_ID(), '_view_count', true);
                            $author_id = get_the_author_meta('ID');
                            $author_avatar = get_avatar_url($author_id, array('size' => 36));
                            
                            if (!$view_count) $view_count = 0;
                    ?>
                        <div class="video-card" onclick="location.href='<?php echo get_permalink(); ?>'">
                            <div class="video-thumbnail">
                                <?php if ($video_thumbnail): ?>
                                    <img src="<?php echo esc_url($video_thumbnail); ?>" alt="<?php echo esc_attr(get_the_title()); ?>">
                                <?php endif; ?>
                            </div>
                            <div class="video-info">
                                <img src="<?php echo esc_url($author_avatar); ?>" alt="<?php echo esc_attr(get_the_author()); ?>" class="channel-avatar">
                                <div class="video-details">
                                    <h3><?php echo esc_html(get_the_title()); ?></h3>
                                    <div class="video-meta">
                                        <a href="<?php echo get_author_posts_url($author_id); ?>" class="channel-name"><?php echo esc_html(get_the_author()); ?></a><br>
                                        <?php echo number_format($view_count); ?> 次观看
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php
                        endwhile;
                        wp_reset_postdata();
                    endif;
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.error-404 {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 60vh;
    padding: 40px 20px;
}

.error-content {
    text-align: center;
    max-width: 600px;
    width: 100%;
}

.error-icon {
    font-size: 80px;
    color: #ff6b6b;
    margin-bottom: 24px;
}

.error-title {
    font-size: 72px;
    font-weight: 700;
    color: #ffffff;
    margin-bottom: 16px;
    line-height: 1;
}

.error-subtitle {
    font-size: 32px;
    font-weight: 500;
    color: #ffffff;
    margin-bottom: 16px;
}

.error-message {
    font-size: 16px;
    color: #aaaaaa;
    margin-bottom: 32px;
    line-height: 1.6;
}

.error-actions {
    display: flex;
    justify-content: center;
    gap: 16px;
    margin-bottom: 48px;
    flex-wrap: wrap;
}

.btn-primary,
.btn-secondary {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 24px;
    border-radius: 8px;
    text-decoration: none;
    font-size: 16px;
    font-weight: 500;
    cursor: pointer;
    border: none;
    transition: all 0.2s ease;
}

.btn-primary {
    background-color: #3ea6ff;
    color: #ffffff;
}

.btn-primary:hover {
    background-color: #2d8ceb;
    transform: translateY(-2px);
}

.btn-secondary {
    background-color: transparent;
    color: #ffffff;
    border: 1px solid #303030;
}

.btn-secondary:hover {
    background-color: rgba(255, 255, 255, 0.1);
    border-color: #404040;
}

.search-section {
    margin-bottom: 48px;
}

.search-section h3 {
    font-size: 20px;
    font-weight: 500;
    color: #ffffff;
    margin-bottom: 16px;
}

.search-input-group {
    display: flex;
    max-width: 400px;
    margin: 0 auto;
    height: 48px;
}

.search-input-group .search-input {
    flex: 1;
    background-color: #212121;
    border: 1px solid #303030;
    color: #ffffff;
    padding: 0 16px;
    font-size: 16px;
    border-radius: 24px 0 0 24px;
    border-right: none;
}

.search-input-group .search-input:focus {
    outline: none;
    border-color: #3ea6ff;
}

.search-input-group .search-button {
    background-color: #303030;
    border: 1px solid #303030;
    color: #ffffff;
    padding: 0 20px;
    cursor: pointer;
    border-radius: 0 24px 24px 0;
    border-left: none;
}

.search-input-group .search-button:hover {
    background-color: #404040;
}

.popular-videos {
    text-align: left;
}

.popular-videos h3 {
    font-size: 20px;
    font-weight: 500;
    color: #ffffff;
    margin-bottom: 24px;
    text-align: center;
}

.popular-videos .video-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 20px;
    max-width: 900px;
    margin: 0 auto;
}

/* Animation */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.error-content > * {
    animation: fadeInUp 0.6s ease forwards;
}

.error-content > *:nth-child(2) { animation-delay: 0.1s; }
.error-content > *:nth-child(3) { animation-delay: 0.2s; }
.error-content > *:nth-child(4) { animation-delay: 0.3s; }
.error-content > *:nth-child(5) { animation-delay: 0.4s; }
.error-content > *:nth-child(6) { animation-delay: 0.5s; }
.error-content > *:nth-child(7) { animation-delay: 0.6s; }

@media (max-width: 768px) {
    .error-title {
        font-size: 48px;
    }
    
    .error-subtitle {
        font-size: 24px;
    }
    
    .error-icon {
        font-size: 60px;
    }
    
    .error-actions {
        flex-direction: column;
        align-items: center;
    }
    
    .btn-primary,
    .btn-secondary {
        width: 200px;
        justify-content: center;
    }
    
    .search-input-group {
        max-width: 100%;
    }
    
    .popular-videos .video-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 480px) {
    .error-404 {
        padding: 20px 16px;
    }
    
    .error-title {
        font-size: 36px;
    }
    
    .error-subtitle {
        font-size: 20px;
    }
    
    .error-message {
        font-size: 14px;
    }
}
</style>

<?php get_footer(); ?> 
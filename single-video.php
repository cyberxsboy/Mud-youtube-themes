<?php
/**
 * Single Video Template
 * 
 * This template displays individual video posts with player,
 * video information, channel details, and comments.
 * 
 * @package Mud_YouTube_Themes
 * @version 1.0.0
 * @author 泥人传说
 * @author_uri https://nirenchuanshuo.com
 * @since 1.0.0
 */

get_header(); ?>

<div class="main-content">
    <?php while (have_posts()) : the_post(); ?>
    
    <div class="video-player-container">
        <div class="video-primary">
            <div class="video-player">
                <?php 
                $video_file = get_post_meta(get_the_ID(), '_video_file', true);
                $video_thumbnail = get_post_meta(get_the_ID(), '_video_thumbnail', true);
                if ($video_file): 
                ?>
                    <video controls poster="<?php echo esc_url($video_thumbnail); ?>">
                        <source src="<?php echo esc_url($video_file); ?>" type="video/mp4">
                        您的浏览器不支持视频播放。
                    </video>
                <?php endif; ?>
            </div>
            
            <h1 class="video-title"><?php the_title(); ?></h1>
            
            <div class="video-stats">
                <div class="video-views">
                    <?php 
                    $view_count = get_post_meta(get_the_ID(), '_view_count', true);
                    echo number_format($view_count ? $view_count : 0); 
                    ?> 次观看 • <?php echo get_the_date(); ?>
                </div>
                <div class="video-actions">
                    <button class="action-button">
                        <i class="fas fa-thumbs-up"></i>
                        <?php 
                        $like_count = get_post_meta(get_the_ID(), '_like_count', true);
                        echo number_format($like_count ? $like_count : 0); 
                        ?>
                    </button>
                    <button class="action-button">
                        <i class="fas fa-share"></i>
                        分享
                    </button>
                </div>
            </div>
            
            <div class="channel-info">
                <?php 
                $author_id = get_the_author_meta('ID');
                $author_avatar = get_avatar_url($author_id, array('size' => 48)); 
                ?>
                <img src="<?php echo esc_url($author_avatar); ?>" class="channel-avatar-large">
                <div class="channel-details">
                    <h4><?php echo esc_html(get_the_author()); ?></h4>
                    <div class="subscriber-count">订阅者</div>
                </div>
                <?php if (is_user_logged_in()): ?>
                    <button class="subscribe-button">订阅</button>
                <?php endif; ?>
            </div>
            
            <div class="video-description">
                <?php the_content(); ?>
            </div>
            
            <?php if (comments_open()): ?>
                <div class="comments-section">
                    <h3><?php echo get_comments_number(); ?> 条评论</h3>
                    <?php comments_template(); ?>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="video-sidebar">
            相关视频
        </div>
    </div>
    
    <?php endwhile; ?>
</div>

<?php get_footer(); ?> 
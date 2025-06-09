<?php
/**
 * The main template file
 * 
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * 
 * @package Mud_YouTube_Themes
 * @version 1.0.0
 * @author 泥人传说
 * @author_uri https://nirenchuanshuo.com
 * @since 1.0.0
 */

get_header(); ?>

<div class="main-content">
    <div class="video-grid">
        <?php
        // Query for videos (custom post type)
        $video_query = new WP_Query(array(
            'post_type' => 'video',
            'posts_per_page' => 24,
            'post_status' => 'publish',
            'meta_query' => array(
                array(
                    'key' => '_video_file',
                    'compare' => 'EXISTS'
                )
            )
        ));

        if ($video_query->have_posts()) :
            while ($video_query->have_posts()) : $video_query->the_post();
                $video_file = get_post_meta(get_the_ID(), '_video_file', true);
                $video_thumbnail = get_post_meta(get_the_ID(), '_video_thumbnail', true);
                $video_duration = get_post_meta(get_the_ID(), '_video_duration', true);
                $view_count = get_post_meta(get_the_ID(), '_view_count', true);
                $author_id = get_the_author_meta('ID');
                $author_avatar = get_avatar_url($author_id, array('size' => 36));
                
                if (!$video_thumbnail) {
                    $video_thumbnail = get_the_post_thumbnail_url(get_the_ID(), 'medium');
                }
                
                if (!$view_count) {
                    $view_count = 0;
                }
        ?>
            <div class="video-card" onclick="location.href='<?php echo get_permalink(); ?>'">
                <div class="video-thumbnail">
                    <?php if ($video_thumbnail): ?>
                        <img src="<?php echo esc_url($video_thumbnail); ?>" alt="<?php echo esc_attr(get_the_title()); ?>">
                    <?php endif; ?>
                    <?php if ($video_duration): ?>
                        <span class="video-duration"><?php echo esc_html($video_duration); ?></span>
                    <?php endif; ?>
                </div>
                <div class="video-info">
                    <img src="<?php echo esc_url($author_avatar); ?>" alt="<?php echo esc_attr(get_the_author()); ?>" class="channel-avatar">
                    <div class="video-details">
                        <h3><?php echo esc_html(get_the_title()); ?></h3>
                        <div class="video-meta">
                            <a href="<?php echo get_author_posts_url($author_id); ?>" class="channel-name"><?php echo esc_html(get_the_author()); ?></a><br>
                            <?php echo number_format($view_count); ?> 次观看 • <?php echo human_time_diff(get_the_time('U'), current_time('timestamp')); ?>前
                        </div>
                    </div>
                </div>
            </div>
        <?php
            endwhile;
            wp_reset_postdata();
        else:
        ?>
            <div class="no-videos">
                <h2>暂无视频</h2>
                <p>还没有上传任何视频。</p>
                <?php if (is_user_logged_in()): ?>
                    <a href="<?php echo home_url('/upload'); ?>" class="upload-button">上传第一个视频</a>
                <?php else: ?>
                    <a href="<?php echo wp_login_url(); ?>" class="upload-button">登录后上传视频</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php get_footer(); ?> 
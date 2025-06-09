<?php
/**
 * Search Results Template
 */

get_header(); ?>

<div class="main-content">
    <div class="search-results">
        <h1 class="search-title">
            <?php
            $search_query = get_search_query();
            if ($search_query) {
                printf('搜索结果："%s"', esc_html($search_query));
            } else {
                echo '搜索结果';
            }
            ?>
        </h1>
        
        <?php if (have_posts()): ?>
            <div class="search-info">
                找到 <?php echo $wp_query->found_posts; ?> 个相关视频
            </div>
            
            <div class="video-grid">
                <?php while (have_posts()): the_post(); 
                    $video_file = get_post_meta(get_the_ID(), '_video_file', true);
                    $video_thumbnail = get_post_meta(get_the_ID(), '_video_thumbnail', true);
                    $video_duration = get_post_meta(get_the_ID(), '_video_duration', true);
                    $view_count = get_post_meta(get_the_ID(), '_view_count', true);
                    $author_id = get_the_author_meta('ID');
                    $author_avatar = get_avatar_url($author_id, array('size' => 36));
                    
                    if (!$video_thumbnail) {
                        $video_thumbnail = get_the_post_thumbnail_url(get_the_ID(), 'medium');
                    }
                    if (!$view_count) $view_count = 0;
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
                <?php endwhile; ?>
            </div>
            
            <?php
            // Pagination
            the_posts_pagination(array(
                'mid_size' => 2,
                'prev_text' => '<i class="fas fa-chevron-left"></i> 上一页',
                'next_text' => '下一页 <i class="fas fa-chevron-right"></i>',
            ));
            ?>
            
        <?php else: ?>
            <div class="no-results">
                <div class="no-results-icon">
                    <i class="fas fa-search"></i>
                </div>
                <h2>未找到相关视频</h2>
                <p>尝试使用不同的关键词或检查拼写</p>
                
                <div class="search-suggestions">
                    <h3>搜索建议：</h3>
                    <ul>
                        <li>使用更通用的关键词</li>
                        <li>检查拼写是否正确</li>
                        <li>尝试使用同义词</li>
                        <li>减少搜索词的数量</li>
                    </ul>
                </div>
                
                <div class="popular-videos">
                    <h3>热门视频</h3>
                    <div class="video-grid">
                        <?php
                        $popular_videos = new WP_Query(array(
                            'post_type' => 'video',
                            'posts_per_page' => 8,
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
        <?php endif; ?>
    </div>
</div>

<style>
.search-results {
    max-width: 1284px;
    margin: 0 auto;
}

.search-title {
    font-size: 24px;
    font-weight: 400;
    margin-bottom: 16px;
    color: #ffffff;
}

.search-info {
    color: #aaaaaa;
    font-size: 14px;
    margin-bottom: 24px;
    padding-bottom: 16px;
    border-bottom: 1px solid #303030;
}

.no-results {
    text-align: center;
    padding: 60px 20px;
}

.no-results-icon {
    font-size: 64px;
    color: #666666;
    margin-bottom: 24px;
}

.no-results h2 {
    font-size: 24px;
    font-weight: 400;
    margin-bottom: 16px;
    color: #ffffff;
}

.no-results p {
    color: #aaaaaa;
    font-size: 16px;
    margin-bottom: 32px;
}

.search-suggestions {
    background-color: #212121;
    padding: 24px;
    border-radius: 12px;
    margin-bottom: 40px;
    text-align: left;
    max-width: 500px;
    margin-left: auto;
    margin-right: auto;
}

.search-suggestions h3 {
    font-size: 18px;
    font-weight: 500;
    margin-bottom: 16px;
    color: #ffffff;
}

.search-suggestions ul {
    list-style: none;
    padding: 0;
}

.search-suggestions li {
    color: #aaaaaa;
    font-size: 14px;
    margin-bottom: 8px;
    padding-left: 20px;
    position: relative;
}

.search-suggestions li:before {
    content: '•';
    color: #3ea6ff;
    position: absolute;
    left: 0;
}

.popular-videos {
    text-align: left;
}

.popular-videos h3 {
    font-size: 20px;
    font-weight: 500;
    margin-bottom: 24px;
    color: #ffffff;
}

/* Pagination styles */
.pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-top: 40px;
    gap: 8px;
}

.pagination .page-numbers {
    background-color: #303030;
    color: #ffffff;
    padding: 8px 16px;
    border-radius: 8px;
    text-decoration: none;
    font-size: 14px;
    transition: background-color 0.2s ease;
}

.pagination .page-numbers:hover,
.pagination .page-numbers.current {
    background-color: #3ea6ff;
}

.pagination .page-numbers.prev,
.pagination .page-numbers.next {
    display: flex;
    align-items: center;
    gap: 8px;
}

@media (max-width: 768px) {
    .search-title {
        font-size: 20px;
    }
    
    .no-results {
        padding: 40px 16px;
    }
    
    .no-results-icon {
        font-size: 48px;
    }
    
    .search-suggestions {
        margin-bottom: 32px;
    }
}
</style>

<?php get_footer(); ?> 
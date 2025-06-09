<?php
/**
 * Author/Channel Template
 */

get_header(); 

$author_id = get_queried_object_id();
$author = get_userdata($author_id);
$channel_description = get_user_meta($author_id, 'channel_description', true);
$channel_banner = get_user_meta($author_id, 'channel_banner', true);
$subscriber_count = get_user_meta($author_id, '_subscriber_count', true);

if (!$subscriber_count) $subscriber_count = 0;

// Check if current user is subscribed
$user_subscribed = false;
if (is_user_logged_in()) {
    $current_user_id = get_current_user_id();
    $subscriptions = get_user_meta($current_user_id, '_subscriptions', true);
    if (is_array($subscriptions)) {
        $user_subscribed = in_array($author_id, $subscriptions);
    }
}
?>

<div class="main-content">
    <div class="channel-page">
        <!-- Channel Banner -->
        <?php if ($channel_banner): ?>
            <div class="channel-banner">
                <img src="<?php echo esc_url($channel_banner); ?>" alt="<?php echo esc_attr($author->display_name); ?> 频道横幅">
            </div>
        <?php endif; ?>
        
        <!-- Channel Header -->
        <div class="channel-header">
            <div class="channel-avatar-section">
                <?php echo get_avatar($author_id, 80, '', '', array('class' => 'channel-avatar-large')); ?>
            </div>
            
            <div class="channel-info-section">
                <h1 class="channel-name"><?php echo esc_html($author->display_name); ?></h1>
                <div class="channel-stats">
                    <span class="subscriber-count"><?php echo mudtube_format_number($subscriber_count); ?> 位订阅者</span>
                    <span class="video-count">
                        <?php echo count_user_posts($author_id, 'video') . ' 个视频'; ?>
                    </span>
                </div>
                
                <?php if ($channel_description): ?>
                    <div class="channel-description">
                        <p><?php echo wp_kses_post($channel_description); ?></p>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="channel-actions">
                <?php if (is_user_logged_in() && get_current_user_id() !== $author_id): ?>
                    <button class="subscribe-button <?php echo $user_subscribed ? 'subscribed' : ''; ?>" 
                            data-channel-id="<?php echo $author_id; ?>">
                        <?php echo $user_subscribed ? '已订阅' : '订阅'; ?>
                    </button>
                <?php elseif (is_user_logged_in() && get_current_user_id() === $author_id): ?>
                    <a href="<?php echo home_url('/upload'); ?>" class="upload-button">
                        <i class="fas fa-plus"></i>
                        上传视频
                    </a>
                    <a href="<?php echo admin_url('profile.php'); ?>" class="edit-channel-button">
                        <i class="fas fa-edit"></i>
                        编辑频道
                    </a>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Channel Navigation -->
        <div class="channel-nav">
            <div class="nav-tabs">
                <button class="nav-tab active" data-tab="videos">
                    <i class="fas fa-play"></i>
                    视频
                </button>
                <button class="nav-tab" data-tab="playlists">
                    <i class="fas fa-list"></i>
                    播放列表
                </button>
                <button class="nav-tab" data-tab="about">
                    <i class="fas fa-info-circle"></i>
                    简介
                </button>
            </div>
        </div>
        
        <!-- Channel Content -->
        <div class="channel-content">
            
            <!-- Videos Tab -->
            <div class="tab-content active" id="videos-tab">
                <?php
                $videos_query = new WP_Query(array(
                    'post_type' => 'video',
                    'author' => $author_id,
                    'posts_per_page' => 20,
                    'post_status' => 'publish'
                ));
                
                if ($videos_query->have_posts()):
                ?>
                    <div class="videos-section">
                        <div class="section-header">
                            <h2>上传的视频</h2>
                            <div class="sort-options">
                                <select id="video-sort">
                                    <option value="date">最新上传</option>
                                    <option value="popular">最受欢迎</option>
                                    <option value="oldest">最早上传</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="video-grid">
                            <?php while ($videos_query->have_posts()): $videos_query->the_post();
                                $video_thumbnail = get_post_meta(get_the_ID(), '_video_thumbnail', true);
                                $video_duration = get_post_meta(get_the_ID(), '_video_duration', true);
                                $view_count = get_post_meta(get_the_ID(), '_view_count', true);
                                
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
                                        <div class="video-details">
                                            <h3><?php echo esc_html(get_the_title()); ?></h3>
                                            <div class="video-meta">
                                                <?php echo number_format($view_count); ?> 次观看 • <?php echo human_time_diff(get_the_time('U'), current_time('timestamp')); ?>前
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="no-videos">
                        <div class="no-videos-icon">
                            <i class="fas fa-video"></i>
                        </div>
                        <h3>此频道还没有上传任何视频</h3>
                        <?php if (is_user_logged_in() && get_current_user_id() === $author_id): ?>
                            <p>开始创建内容，与观众分享您的故事</p>
                            <a href="<?php echo home_url('/upload'); ?>" class="upload-button">
                                <i class="fas fa-plus"></i>
                                上传第一个视频
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; wp_reset_postdata(); ?>
            </div>
            
            <!-- Playlists Tab -->
            <div class="tab-content" id="playlists-tab">
                <div class="no-playlists">
                    <div class="no-playlists-icon">
                        <i class="fas fa-list"></i>
                    </div>
                    <h3>此频道还没有创建播放列表</h3>
                </div>
            </div>
            
            <!-- About Tab -->
            <div class="tab-content" id="about-tab">
                <div class="about-section">
                    <div class="about-info">
                        <h3>频道信息</h3>
                        
                        <?php if ($channel_description): ?>
                            <div class="description">
                                <h4>描述</h4>
                                <p><?php echo wp_kses_post($channel_description); ?></p>
                            </div>
                        <?php endif; ?>
                        
                        <div class="stats">
                            <h4>统计信息</h4>
                            <div class="stat-item">
                                <span class="stat-label">加入时间：</span>
                                <span class="stat-value"><?php echo date('Y年n月j日', strtotime($author->user_registered)); ?></span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-label">总观看次数：</span>
                                <span class="stat-value">
                                    <?php
                                    $total_views = 0;
                                    $author_videos = get_posts(array(
                                        'post_type' => 'video',
                                        'author' => $author_id,
                                        'posts_per_page' => -1,
                                        'fields' => 'ids'
                                    ));
                                    
                                    foreach ($author_videos as $video_id) {
                                        $views = get_post_meta($video_id, '_view_count', true);
                                        $total_views += $views ? $views : 0;
                                    }
                                    
                                    echo number_format($total_views);
                                    ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
jQuery(document).ready(function($) {
    // Tab switching
    $('.nav-tab').on('click', function() {
        const tabId = $(this).data('tab');
        
        // Update active tab
        $('.nav-tab').removeClass('active');
        $(this).addClass('active');
        
        // Update active content
        $('.tab-content').removeClass('active');
        $('#' + tabId + '-tab').addClass('active');
    });
    
    // Video sorting
    $('#video-sort').on('change', function() {
        const sortBy = $(this).val();
        const $videoGrid = $('.video-grid');
        const $videos = $videoGrid.find('.video-card');
        
        // Simple client-side sorting (in a real implementation, this would be server-side)
        $videos.sort(function(a, b) {
            // This is a placeholder - implement actual sorting logic
            return 0;
        });
        
        $videoGrid.empty().append($videos);
    });
    
    // Subscribe button (reuse from main.js)
    $('.subscribe-button').on('click', function(e) {
        e.preventDefault();
        
        if (!mudtube_ajax) return;
        
        const $button = $(this);
        const channelId = $button.data('channel-id');
        
        if (!channelId) return;
        
        $button.prop('disabled', true);
        
        $.ajax({
            url: mudtube_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'mudtube_subscribe',
                channel_id: channelId,
                nonce: mudtube_ajax.nonce
            },
            success: function(response) {
                if (response.success) {
                    if (response.data.subscribed) {
                        $button.addClass('subscribed').text('已订阅');
                    } else {
                        $button.removeClass('subscribed').text('订阅');
                    }
                    
                    // Update subscriber count
                    $('.subscriber-count').text(formatNumber(response.data.subscriber_count) + ' 位订阅者');
                }
            },
            complete: function() {
                $button.prop('disabled', false);
            }
        });
    });
    
    function formatNumber(num) {
        if (num >= 1000000) {
            return (num / 1000000).toFixed(1) + 'M';
        } else if (num >= 1000) {
            return (num / 1000).toFixed(1) + 'K';
        }
        return num.toString();
    }
});
</script>

<style>
.channel-page {
    max-width: 1284px;
    margin: 0 auto;
}

.channel-banner {
    width: 100%;
    height: 200px;
    overflow: hidden;
    border-radius: 12px;
    margin-bottom: 24px;
}

.channel-banner img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.channel-header {
    display: flex;
    align-items: flex-start;
    gap: 24px;
    margin-bottom: 32px;
    padding-bottom: 24px;
    border-bottom: 1px solid #303030;
}

.channel-avatar-section .channel-avatar-large {
    width: 80px;
    height: 80px;
    border-radius: 50%;
}

.channel-info-section {
    flex: 1;
}

.channel-name {
    font-size: 24px;
    font-weight: 500;
    margin-bottom: 8px;
    color: #ffffff;
}

.channel-stats {
    display: flex;
    gap: 16px;
    margin-bottom: 12px;
    color: #aaaaaa;
    font-size: 14px;
}

.channel-description p {
    color: #aaaaaa;
    font-size: 14px;
    line-height: 1.5;
}

.channel-actions {
    display: flex;
    gap: 12px;
    align-items: flex-start;
}

.edit-channel-button {
    background-color: transparent;
    border: 1px solid #303030;
    color: #ffffff;
    padding: 8px 16px;
    border-radius: 18px;
    text-decoration: none;
    font-size: 14px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.edit-channel-button:hover {
    background-color: rgba(255, 255, 255, 0.1);
}

.channel-nav {
    margin-bottom: 32px;
}

.nav-tabs {
    display: flex;
    gap: 32px;
    border-bottom: 1px solid #303030;
}

.nav-tab {
    background: none;
    border: none;
    color: #aaaaaa;
    padding: 12px 0;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 8px;
    border-bottom: 2px solid transparent;
    transition: all 0.2s ease;
}

.nav-tab:hover,
.nav-tab.active {
    color: #ffffff;
    border-bottom-color: #3ea6ff;
}

.tab-content {
    display: none;
}

.tab-content.active {
    display: block;
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
}

.section-header h2 {
    font-size: 20px;
    font-weight: 500;
    color: #ffffff;
}

.sort-options select {
    background-color: #303030;
    border: 1px solid #404040;
    color: #ffffff;
    padding: 8px 12px;
    border-radius: 8px;
    font-size: 14px;
}

.no-videos,
.no-playlists {
    text-align: center;
    padding: 60px 20px;
}

.no-videos-icon,
.no-playlists-icon {
    font-size: 64px;
    color: #666666;
    margin-bottom: 24px;
}

.no-videos h3,
.no-playlists h3 {
    font-size: 20px;
    font-weight: 400;
    margin-bottom: 16px;
    color: #ffffff;
}

.no-videos p {
    color: #aaaaaa;
    margin-bottom: 24px;
}

.about-section {
    max-width: 800px;
}

.about-info h3 {
    font-size: 20px;
    font-weight: 500;
    margin-bottom: 24px;
    color: #ffffff;
}

.description {
    margin-bottom: 32px;
}

.description h4 {
    font-size: 16px;
    font-weight: 500;
    margin-bottom: 12px;
    color: #ffffff;
}

.description p {
    color: #aaaaaa;
    line-height: 1.6;
}

.stats h4 {
    font-size: 16px;
    font-weight: 500;
    margin-bottom: 16px;
    color: #ffffff;
}

.stat-item {
    display: flex;
    margin-bottom: 8px;
}

.stat-label {
    color: #aaaaaa;
    min-width: 120px;
}

.stat-value {
    color: #ffffff;
}

@media (max-width: 768px) {
    .channel-header {
        flex-direction: column;
        align-items: center;
        text-align: center;
        gap: 16px;
    }
    
    .channel-actions {
        width: 100%;
        justify-content: center;
    }
    
    .nav-tabs {
        gap: 16px;
        overflow-x: auto;
        padding-bottom: 8px;
    }
    
    .nav-tab {
        white-space: nowrap;
        min-width: fit-content;
    }
    
    .section-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 16px;
    }
}
</style>

<?php get_footer(); ?> 
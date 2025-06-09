<?php
/**
 * Comments Template
 * 
 * The template for displaying comments
 */

if (post_password_required()) {
    return;
}
?>

<div id="comments" class="comments-area">
    <?php if (have_comments()): ?>
        <h3 class="comments-title">
            <?php
            $comments_number = get_comments_number();
            if ($comments_number == 1) {
                echo '1 条评论';
            } else {
                printf('%s 条评论', number_format_i18n($comments_number));
            }
            ?>
        </h3>

        <ol class="comment-list">
            <?php
            wp_list_comments(array(
                'style' => 'ol',
                'short_ping' => true,
                'avatar_size' => 40,
                'callback' => 'mudtube_comment_callback'
            ));
            ?>
        </ol>

        <?php if (get_comment_pages_count() > 1 && get_option('page_comments')): ?>
            <nav class="comment-navigation">
                <div class="nav-previous"><?php previous_comments_link('&larr; 较早评论'); ?></div>
                <div class="nav-next"><?php next_comments_link('较新评论 &rarr;'); ?></div>
            </nav>
        <?php endif; ?>

    <?php endif; ?>

    <?php if (!comments_open() && get_comments_number() && post_type_supports(get_post_type(), 'comments')): ?>
        <p class="no-comments">评论已关闭。</p>
    <?php endif; ?>

    <?php
    // Comment form
    if (comments_open()):
        $commenter = wp_get_current_commenter();
        $req = get_option('require_name_email');
        $aria_req = ($req ? " aria-required='true'" : '');

        $comment_form_args = array(
            'title_reply' => '发表评论',
            'title_reply_to' => '回复 %s',
            'cancel_reply_link' => '取消回复',
            'label_submit' => '发表评论',
            'comment_field' => '<p class="comment-form-comment"><label for="comment">评论</label><textarea id="comment" name="comment" cols="45" rows="8" aria-required="true" placeholder="写下您的评论..."></textarea></p>',
            'must_log_in' => '<p class="must-log-in">' . sprintf('您必须<a href="%s">登录</a>才能发表评论。', wp_login_url(apply_filters('the_permalink', get_permalink()))) . '</p>',
            'logged_in_as' => '<p class="logged-in-as">' . sprintf('以 <a href="%1$s">%2$s</a> 身份登录。<a href="%3$s" title="退出此账户">退出？</a>', admin_url('profile.php'), $user_identity, wp_logout_url(apply_filters('the_permalink', get_permalink()))) . '</p>',
            'comment_notes_before' => '<p class="comment-notes">您的邮箱地址不会被公开。必填项已用 * 标注</p>',
            'comment_notes_after' => '',
            'fields' => array(
                'author' => '<p class="comment-form-author"><label for="author">姓名' . ($req ? ' *' : '') . '</label> <input id="author" name="author" type="text" value="' . esc_attr($commenter['comment_author']) . '" size="30"' . $aria_req . ' /></p>',
                'email' => '<p class="comment-form-email"><label for="email">邮箱' . ($req ? ' *' : '') . '</label> <input id="email" name="email" type="email" value="' . esc_attr($commenter['comment_author_email']) . '" size="30"' . $aria_req . ' /></p>',
                'url' => '<p class="comment-form-url"><label for="url">网站</label><input id="url" name="url" type="url" value="' . esc_attr($commenter['comment_author_url']) . '" size="30" /></p>'
            )
        );

        comment_form($comment_form_args);
    endif;
    ?>
</div>

<?php
/**
 * Custom comment callback
 */
function mudtube_comment_callback($comment, $args, $depth) {
    $GLOBALS['comment'] = $comment;
    ?>
    <li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
        <div class="comment-body">
            <div class="comment-author vcard">
                <?php echo get_avatar($comment, 40); ?>
                <cite class="fn"><?php comment_author_link(); ?></cite>
                <span class="comment-meta">
                    <a href="<?php echo esc_url(get_comment_link($comment->comment_ID)); ?>">
                        <time datetime="<?php comment_time('c'); ?>">
                            <?php printf('%s前', human_time_diff(get_comment_time('U'), current_time('timestamp'))); ?>
                        </time>
                    </a>
                </span>
            </div>

            <div class="comment-content">
                <?php comment_text(); ?>
            </div>

            <div class="comment-actions">
                <?php
                comment_reply_link(array_merge($args, array(
                    'depth' => $depth,
                    'max_depth' => $args['max_depth'],
                    'reply_text' => '回复'
                )));
                ?>
                
                <button class="comment-like" data-comment-id="<?php comment_ID(); ?>">
                    <i class="fas fa-thumbs-up"></i>
                    <span class="like-count">0</span>
                </button>
                
                <button class="comment-dislike" data-comment-id="<?php comment_ID(); ?>">
                    <i class="fas fa-thumbs-down"></i>
                </button>
            </div>
        </div>
    <?php
}
?>

<style>
.comments-area {
    margin-top: 40px;
}

.comments-title {
    font-size: 18px;
    font-weight: 500;
    margin-bottom: 24px;
    color: #ffffff;
}

.comment-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.comment-list .comment {
    margin-bottom: 24px;
    padding: 16px 0;
    border-bottom: 1px solid #303030;
}

.comment-body {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.comment-author {
    display: flex;
    align-items: center;
    gap: 12px;
}

.comment-author img {
    border-radius: 50%;
}

.comment-author .fn {
    font-weight: 500;
    color: #ffffff;
    text-decoration: none;
}

.comment-meta {
    color: #aaaaaa;
    font-size: 12px;
}

.comment-meta a {
    color: #aaaaaa;
    text-decoration: none;
}

.comment-content {
    color: #ffffff;
    line-height: 1.5;
    margin-left: 52px;
}

.comment-content p {
    margin: 0;
}

.comment-actions {
    display: flex;
    align-items: center;
    gap: 16px;
    margin-left: 52px;
}

.comment-actions a,
.comment-actions button {
    background: none;
    border: none;
    color: #aaaaaa;
    font-size: 12px;
    cursor: pointer;
    padding: 4px 8px;
    border-radius: 4px;
    display: flex;
    align-items: center;
    gap: 4px;
    text-decoration: none;
}

.comment-actions a:hover,
.comment-actions button:hover {
    background-color: rgba(255, 255, 255, 0.1);
    color: #ffffff;
}

.comment-form {
    margin-top: 32px;
    background-color: #212121;
    padding: 24px;
    border-radius: 12px;
}

.comment-form h3 {
    color: #ffffff;
    margin-bottom: 16px;
}

.comment-form p {
    margin-bottom: 16px;
}

.comment-form label {
    display: block;
    margin-bottom: 4px;
    color: #aaaaaa;
    font-size: 14px;
}

.comment-form input,
.comment-form textarea {
    width: 100%;
    background-color: #303030;
    border: 1px solid #404040;
    color: #ffffff;
    padding: 12px;
    border-radius: 8px;
    font-size: 14px;
    font-family: inherit;
}

.comment-form input:focus,
.comment-form textarea:focus {
    outline: none;
    border-color: #3ea6ff;
}

.comment-form textarea {
    resize: vertical;
    min-height: 100px;
}

.comment-form .form-submit {
    margin: 0;
}

.comment-form #submit {
    background-color: #3ea6ff;
    border: none;
    color: #ffffff;
    padding: 12px 24px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    width: auto;
}

.comment-form #submit:hover {
    background-color: #2d8ceb;
}

.comment-navigation {
    display: flex;
    justify-content: space-between;
    margin: 24px 0;
}

.comment-navigation a {
    color: #3ea6ff;
    text-decoration: none;
    padding: 8px 16px;
    border-radius: 8px;
    background-color: #212121;
}

.comment-navigation a:hover {
    background-color: #303030;
}

.no-comments {
    color: #aaaaaa;
    text-align: center;
    padding: 24px;
    background-color: #212121;
    border-radius: 8px;
}

.must-log-in,
.logged-in-as {
    background-color: #212121;
    padding: 16px;
    border-radius: 8px;
    margin-bottom: 16px;
}

.must-log-in a,
.logged-in-as a {
    color: #3ea6ff;
    text-decoration: none;
}

.must-log-in a:hover,
.logged-in-as a:hover {
    text-decoration: underline;
}

.comment-notes {
    color: #aaaaaa;
    font-size: 12px;
    margin-bottom: 16px;
}

/* Nested comments */
.comment-list .children {
    list-style: none;
    margin-left: 52px;
    margin-top: 16px;
}

.comment-list .children .comment {
    border-left: 2px solid #303030;
    padding-left: 16px;
}

@media (max-width: 768px) {
    .comment-content,
    .comment-actions {
        margin-left: 0;
    }
    
    .comment-author {
        flex-direction: column;
        align-items: flex-start;
        gap: 8px;
    }
    
    .comment-list .children {
        margin-left: 16px;
    }
}
</style> 
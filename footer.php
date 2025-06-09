<?php
/**
 * The footer template file
 * 
 * This template displays the site footer with links and copyright information.
 * 
 * @package Mud_YouTube_Themes
 * @version 1.0.0
 * @author 泥人传说
 * @author_uri https://nirenchuanshuo.com
 * @since 1.0.0
 */
?>
<footer class="site-footer">
    <div class="footer-content">
        <div class="footer-links">
            <a href="<?php echo home_url('/about'); ?>">关于</a>
            <a href="<?php echo home_url('/press'); ?>">媒体</a>
            <a href="<?php echo home_url('/copyright'); ?>">版权</a>
            <a href="<?php echo home_url('/contact'); ?>">联系我们</a>
            <a href="<?php echo home_url('/creators'); ?>">创作者</a>
            <a href="<?php echo home_url('/advertise'); ?>">广告</a>
            <a href="<?php echo home_url('/developers'); ?>">开发者</a>
        </div>
        <div class="footer-links">
            <a href="<?php echo home_url('/terms'); ?>">条款</a>
            <a href="<?php echo home_url('/privacy'); ?>">隐私权</a>
            <a href="<?php echo home_url('/policy'); ?>">政策和安全</a>
            <a href="<?php echo home_url('/how-youtube-works'); ?>">YouTube 的运作方式</a>
            <a href="<?php echo home_url('/test-new-features'); ?>">测试新功能</a>
        </div>
        <div class="footer-copyright">
            <p>&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?> | 主题作者：<a href="https://nirenchuanshuo.com" target="_blank" style="color: #aaaaaa;">泥人传说</a></p>
        </div>
    </div>
</footer>

<style>
.site-footer {
    background-color: #212121;
    padding: 32px 24px;
    margin-top: 48px;
    border-top: 1px solid #303030;
}

.footer-content {
    max-width: 1200px;
    margin: 0 auto;
}

.footer-links {
    display: flex;
    flex-wrap: wrap;
    gap: 16px;
    margin-bottom: 16px;
}

.footer-links a {
    color: #aaaaaa;
    text-decoration: none;
    font-size: 13px;
}

.footer-links a:hover {
    color: #ffffff;
}

.footer-copyright {
    color: #666666;
    font-size: 12px;
    margin-top: 16px;
}

@media (max-width: 768px) {
    .footer-links {
        flex-direction: column;
        gap: 8px;
    }
}
</style>

<?php wp_footer(); ?>
</body>
</html> 
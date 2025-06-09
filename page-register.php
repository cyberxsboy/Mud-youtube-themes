<?php
/**
 * Template Name: Register Page
 * 
 * This template displays the user registration form.
 * 
 * @package Mud_YouTube_Themes
 * @version 1.0.0
 * @author 泥人传说
 * @author_uri https://nirenchuanshuo.com
 * @since 1.0.0
 */

// Redirect if user is already logged in
if (is_user_logged_in()) {
    wp_redirect(home_url());
    exit;
}

get_header(); ?>

<div class="main-content">
    <div class="auth-form">
        <h2>注册账户</h2>
        
        <?php
        // Display registration errors
        $errors = get_transient('mudtube_registration_errors');
        if ($errors) {
            echo '<div class="error-messages">';
            foreach ($errors as $error) {
                echo '<p class="error">' . esc_html($error) . '</p>';
            }
            echo '</div>';
            delete_transient('mudtube_registration_errors');
        }
        ?>
        
        <form method="post" action="">
            <div class="form-group">
                <label for="username">用户名</label>
                <input type="text" id="username" name="username" required>
            </div>
            
            <div class="form-group">
                <label for="email">邮箱地址</label>
                <input type="email" id="email" name="email" required>
            </div>
            
            <div class="form-group">
                <label for="password">密码</label>
                <input type="password" id="password" name="password" required minlength="6">
            </div>
            
            <div class="form-group">
                <label for="confirm_password">确认密码</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            
            <button type="submit" name="mudtube_register" class="submit-button">注册</button>
        </form>
        
        <div class="form-links">
            <p>已有账户？ <a href="<?php echo wp_login_url(); ?>">立即登录</a></p>
        </div>
    </div>
</div>

<style>
.error-messages {
    background-color: #ff4444;
    color: white;
    padding: 16px;
    border-radius: 8px;
    margin-bottom: 24px;
}

.error {
    margin: 0;
    font-size: 14px;
}

.form-group input:invalid {
    border-color: #ff4444;
}

.form-group input:valid {
    border-color: #00ff00;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const password = document.getElementById('password');
    const confirmPassword = document.getElementById('confirm_password');
    
    function validatePassword() {
        if (password.value !== confirmPassword.value) {
            confirmPassword.setCustomValidity('密码不匹配');
        } else {
            confirmPassword.setCustomValidity('');
        }
    }
    
    password.addEventListener('change', validatePassword);
    confirmPassword.addEventListener('keyup', validatePassword);
});
</script>

<?php get_footer(); ?> 
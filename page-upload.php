<?php
/**
 * Template Name: Upload Page
 * 
 * This template displays the video upload form for logged-in users.
 * 
 * @package Mud_YouTube_Themes
 * @version 1.0.0
 * @author 泥人传说
 * @author_uri https://nirenchuanshuo.com
 * @since 1.0.0
 */

// Check if user is logged in
if (!is_user_logged_in()) {
    wp_redirect(wp_login_url(get_permalink()));
    exit;
}

get_header(); ?>

<div class="main-content">
    <div class="upload-form">
        <h2>上传视频</h2>
        
        <form method="post" enctype="multipart/form-data" action="">
            <div class="upload-area" id="upload-area">
                <div class="upload-icon">
                    <i class="fas fa-cloud-upload-alt"></i>
                </div>
                <div class="upload-text">选择要上传的视频文件</div>
                <div class="upload-subtext">或将文件拖拽到此处</div>
                <input type="file" id="video-file" name="video_file" accept="video/*" class="file-input" required>
            </div>
            
            <div class="form-group">
                <label for="video_title">视频标题</label>
                <input type="text" id="video_title" name="video_title" required maxlength="100">
            </div>
            
            <div class="form-group">
                <label for="video_description">视频描述</label>
                <textarea id="video_description" name="video_description" rows="5" maxlength="5000"></textarea>
            </div>
            
            <div class="form-group">
                <label for="video_category">分类</label>
                <select id="video_category" name="video_category">
                    <option value="">选择分类</option>
                    <option value="music">音乐</option>
                    <option value="gaming">游戏</option>
                    <option value="education">教育</option>
                    <option value="entertainment">娱乐</option>
                    <option value="news">新闻</option>
                    <option value="sports">体育</option>
                    <option value="technology">科技</option>
                    <option value="lifestyle">生活</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="video_thumbnail">视频缩略图</label>
                <input type="file" id="video_thumbnail" name="video_thumbnail" accept="image/*">
                <small>可选：上传自定义缩略图</small>
            </div>
            
            <div class="form-group">
                <label>
                    <input type="checkbox" name="video_public" value="1" checked>
                    公开视频（其他用户可以观看）
                </label>
            </div>
            
            <button type="submit" name="mudtube_upload_video" class="submit-button">
                <i class="fas fa-upload"></i>
                上传视频
            </button>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const uploadArea = document.getElementById('upload-area');
    const fileInput = document.getElementById('video-file');
    
    // Click to select file
    uploadArea.addEventListener('click', function() {
        fileInput.click();
    });
    
    // Drag and drop functionality
    uploadArea.addEventListener('dragover', function(e) {
        e.preventDefault();
        uploadArea.classList.add('dragover');
    });
    
    uploadArea.addEventListener('dragleave', function(e) {
        e.preventDefault();
        uploadArea.classList.remove('dragover');
    });
    
    uploadArea.addEventListener('drop', function(e) {
        e.preventDefault();
        uploadArea.classList.remove('dragover');
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            fileInput.files = files;
            updateUploadArea(files[0]);
        }
    });
    
    // File input change
    fileInput.addEventListener('change', function() {
        if (this.files.length > 0) {
            updateUploadArea(this.files[0]);
        }
    });
    
    function updateUploadArea(file) {
        const uploadText = uploadArea.querySelector('.upload-text');
        const uploadSubtext = uploadArea.querySelector('.upload-subtext');
        
        uploadText.textContent = '已选择: ' + file.name;
        uploadSubtext.textContent = '文件大小: ' + formatFileSize(file.size);
        
        // Auto-fill title if empty
        const titleInput = document.getElementById('video_title');
        if (!titleInput.value) {
            const fileName = file.name.replace(/\.[^/.]+$/, ""); // Remove extension
            titleInput.value = fileName;
        }
    }
    
    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }
    
    // Form validation
    const form = uploadArea.closest('form');
    form.addEventListener('submit', function(e) {
        const fileInput = document.getElementById('video-file');
        const titleInput = document.getElementById('video_title');
        
        if (!fileInput.files.length) {
            e.preventDefault();
            alert('请选择要上传的视频文件');
            return;
        }
        
        if (!titleInput.value.trim()) {
            e.preventDefault();
            alert('请输入视频标题');
            titleInput.focus();
            return;
        }
        
        // Show loading state
        const submitButton = form.querySelector('.submit-button');
        submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> 上传中...';
        submitButton.disabled = true;
    });
});
</script>

<style>
.upload-form select {
    width: 100%;
    background-color: #303030;
    border: 1px solid #404040;
    color: #ffffff;
    padding: 12px 16px;
    border-radius: 8px;
    font-size: 16px;
}

.upload-form select:focus {
    outline: none;
    border-color: #3ea6ff;
}

.upload-form textarea {
    width: 100%;
    background-color: #303030;
    border: 1px solid #404040;
    color: #ffffff;
    padding: 12px 16px;
    border-radius: 8px;
    font-size: 16px;
    resize: vertical;
    font-family: inherit;
}

.upload-form textarea:focus {
    outline: none;
    border-color: #3ea6ff;
}

.upload-form small {
    color: #aaaaaa;
    font-size: 12px;
    margin-top: 4px;
    display: block;
}

.upload-form label {
    display: flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
}

.upload-form input[type="checkbox"] {
    width: auto;
    margin: 0;
}

.submit-button:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.upload-area.dragover {
    border-color: #3ea6ff;
    background-color: rgba(62, 166, 255, 0.1);
}
</style>

<?php get_footer(); ?> 
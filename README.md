# Mud YouTube Themes

<div align="center">

![WordPress](https://img.shields.io/badge/WordPress-4.0+-blue.svg)
![PHP](https://img.shields.io/badge/PHP-5.6+-purple.svg)
![License](https://img.shields.io/badge/License-GPL%20v2+-green.svg)
![Version](https://img.shields.io/badge/Version-1.0.0-orange.svg)

**一个完全模仿YouTube设计和功能的WordPress主题**

[演示预览](#) • [下载主题](#) • [文档说明](#) • [问题反馈](#)

</div>

---

## 📖 简介

Mud YouTube Themes 是一个专为WordPress设计的YouTube风格主题，完美复刻了YouTube的界面设计和用户体验。支持视频上传、用户注册、频道管理、订阅系统等完整功能，让您轻松搭建专业的视频分享网站。

**开发者：** [泥人传说](https://nirenchuanshuo.com)  
**版本：** 1.0.0  
**兼容性：** WordPress 4.0+ | PHP 5.6+

---

## ✨ 主要特性

### 🎨 界面设计
- ✅ **完美复刻** - 100%还原YouTube界面设计
- ✅ **深色主题** - 现代化深色UI设计
- ✅ **响应式布局** - 完美适配桌面、平板、手机
- ✅ **流畅动画** - 丰富的交互动画效果

### 🎬 视频功能
- ✅ **视频上传** - 支持多种视频格式上传
- ✅ **自定义缩略图** - 个性化视频封面
- ✅ **视频分类** - 完善的分类管理系统
- ✅ **观看统计** - 实时观看次数统计
- ✅ **时长显示** - 自动识别视频时长
- ✅ **相关推荐** - 智能相关视频推荐

### 👥 用户系统
- ✅ **用户注册/登录** - 完整的用户管理系统
- ✅ **个人频道** - 每个用户都有独立频道页面
- ✅ **订阅系统** - 用户间互相订阅功能
- ✅ **点赞评论** - 完整的互动功能
- ✅ **视频管理** - 用户可管理自己的视频
- ✅ **频道定制** - 自定义频道描述和横幅

### 🔧 技术特性
- ✅ **兼容性强** - 支持WordPress 4.0+所有版本
- ✅ **PHP兼容** - 支持PHP 5.6到8.x所有版本
- ✅ **安全可靠** - 完善的数据验证和安全防护
- ✅ **性能优化** - 优化的数据库查询和缓存机制
- ✅ **SEO友好** - 搜索引擎优化

---

## 🛠️ 系统要求

| 组件 | 最低要求 | 推荐配置 |
|------|----------|----------|
| **WordPress** | 4.0+ | 最新版本 |
| **PHP** | 5.6+ | 7.4+ 或 8.x |
| **MySQL** | 5.6+ | 5.7+ 或 8.0+ |
| **内存** | 128MB | 256MB+ |
| **存储空间** | 50MB | 1GB+ |

---

## 🚀 快速开始

### 方法一：WordPress后台安装

1. 登录WordPress后台
2. 进入 **外观 → 主题**
3. 点击 **添加新主题**
4. 上传主题zip文件
5. **激活主题**

### 方法二：手动安装

```bash
# 1. 下载主题文件
wget https://github.com/cyberxsboy/Mud-youtube-themes/releases/download/v1.0.0-Mud-youtube-themes-worpdress/Mud.youtube.themes.zip

# 2. 解压到主题目录
unzip mud-youtube-themes.zip -d /wp-content/themes/

# 3. 在WordPress后台激活主题
```

### 方法三：自动安装程序

激活主题后，系统会自动提示运行安装程序：

1. 点击 **"立即安装"**
2. 系统自动创建必要页面
3. 配置基本设置
4. 开始使用

---

## ⚙️ 配置指南

### 基础配置

#### 1. 创建必要页面
```
注册页面 → 选择模板 "Register Page"
上传页面 → 选择模板 "Upload Page"
```

#### 2. 设置固定链接
```
设置 → 固定链接 → 选择 "文章名"
```

#### 3. 启用用户注册
```
设置 → 常规 → 勾选 "任何人都可以注册"
```

### 高级配置

#### 自定义Logo
```
外观 → 自定义 → 网站标识 → 上传Logo
```

#### 主题颜色
```
外观 → 自定义 → 主题颜色 → 调整配色
```

---

## 📱 响应式设计

| 设备类型 | 屏幕尺寸 | 特性 |
|----------|----------|------|
| **桌面端** | 1200px+ | 完整功能，最佳体验 |
| **平板端** | 768px-1199px | 优化布局，触控友好 |
| **手机端** | <768px | 移动优先，简洁高效 |

---

## 🎯 使用指南

### 用户注册
1. 访问 `/register` 页面
2. 填写用户名、邮箱、密码
3. 点击 **注册** 按钮

### 视频上传
1. 登录后点击 **"创建"** 按钮
2. 选择或拖拽视频文件
3. 填写视频信息（标题、描述、分类）
4. 上传自定义缩略图（可选）
5. 点击 **"上传视频"**

### 频道管理
1. 进入 **用户 → 个人资料**
2. 填写频道描述
3. 添加频道横幅URL
4. 保存更改

---

## 🔒 安全特性

- 🛡️ **数据验证** - 严格的输入数据验证
- 🛡️ **CSRF保护** - 防止跨站请求伪造
- 🛡️ **SQL注入防护** - 预防SQL注入攻击
- 🛡️ **XSS防护** - 防止跨站脚本攻击
- 🛡️ **登录限制** - 防止暴力破解

---

## ⚡ 性能优化

- 🚀 **图片懒加载** - 提升页面加载速度
- 🚀 **代码压缩** - CSS/JS文件压缩
- 🚀 **数据库优化** - 高效的查询语句
- 🚀 **缓存友好** - 支持各种缓存插件
- 🚀 **CDN支持** - 静态资源CDN加速

---

## 🎨 自定义开发

### 子主题创建

```php
<?php
// style.css
/*
Theme Name: Mud YouTube Themes Child
Template: mud-youtube-themes
Version: 1.0.0
*/

@import url("../mud-youtube-themes/style.css");

/* 你的自定义样式 */
.custom-style {
    /* 自定义代码 */
}
```

### 功能扩展

```php
<?php
// functions.php
function custom_video_function() {
    // 你的自定义功能
}
add_action('init', 'custom_video_function');
```

---

## 🐛 常见问题

<details>
<summary><strong>Q: 视频上传失败怎么办？</strong></summary>

**A:** 检查以下设置：
- 服务器 `upload_max_filesize` 限制
- PHP `post_max_size` 设置
- 服务器磁盘空间
- 文件权限设置
</details>

<details>
<summary><strong>Q: 如何修改视频文件大小限制？</strong></summary>

**A:** 在 `php.ini` 中调整：
```ini
upload_max_filesize = 100M
post_max_size = 100M
max_execution_time = 300
```
</details>

<details>
<summary><strong>Q: 支持哪些视频格式？</strong></summary>

**A:** 支持所有HTML5视频格式：
- MP4 (推荐)
- WebM
- OGV
- MOV
- AVI
</details>

<details>
<summary><strong>Q: 如何创建子主题？</strong></summary>

**A:** 强烈建议使用子主题保护自定义修改：
1. 创建新文件夹 `mud-youtube-themes-child`
2. 创建 `style.css` 和 `functions.php`
3. 在后台激活子主题
</details>

---

## 📞 技术支持

如果您在使用过程中遇到问题：

- 🌐 **官方网站**: [https://nirenchuanshuo.com](https://nirenchuanshuo.com)
- 📧 **技术支持**: 通过官网联系
- 📖 **文档中心**: 查看完整文档
- 🐛 **问题反馈**: 提交Bug报告

---

## 📄 开源协议

本主题基于 **GPL v2** 或更高版本协议发布。

**版权所有 © 2025 [泥人传说](https://nirenchuanshuo.com)**

本程序是自由软件；您可以根据自由软件基金会发布的GNU通用公共许可证的条款重新分发和/或修改它。

---

## 🔄 更新日志

### v1.0.0 (2025-01-XX)
- 🎉 **初始版本发布**
- ✨ 完整YouTube风格界面
- 🎬 基础视频管理功能
- 👥 用户注册和登录系统
- 📱 响应式设计
- 🎯 视频上传和管理
- 📺 频道系统
- ❤️ 订阅和点赞功能
- 💬 评论系统
- 🔍 搜索功能

---

## 🤝 贡献指南

欢迎为项目做出贡献！

1. **Fork** 本仓库
2. 创建您的特性分支 (`git checkout -b feature/AmazingFeature`)
3. 提交您的更改 (`git commit -m 'Add some AmazingFeature'`)
4. 推送到分支 (`git push origin feature/AmazingFeature`)
5. 打开一个 **Pull Request**

---

## 🌟 项目亮点

<div align="center">

### 🎨 完美复刻YouTube界面
![YouTube Interface](https://via.placeholder.com/800x400/0f0f0f/ffffff?text=YouTube+Style+Interface)

### 📱 全面响应式设计
![Responsive Design](https://via.placeholder.com/800x400/212121/ffffff?text=Responsive+Design)

### 🎬 完整视频管理系统
![Video Management](https://via.placeholder.com/800x400/303030/ffffff?text=Video+Management)

</div>

---

## 📊 项目统计

![GitHub stars](https://img.shields.io/github/stars/your-repo/mud-youtube-themes?style=social)
![GitHub forks](https://img.shields.io/github/forks/your-repo/mud-youtube-themes?style=social)
![GitHub issues](https://img.shields.io/github/issues/your-repo/mud-youtube-themes)
![GitHub license](https://img.shields.io/github/license/your-repo/mud-youtube-themes)

---

<div align="center">

**如果这个项目对您有帮助，请给我们一个 ⭐ Star！**

**开发者：[泥人传说](https://nirenchuanshuo.com) | 版权所有 © 2025**

</div> 

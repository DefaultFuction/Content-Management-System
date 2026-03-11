<?php
require_once 'includes/config.php';
;
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo getSetting('site_name'); ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header class="header">
        <div class="container">
            <div class="header-top">
                <div class="logo">
                    <a href="index.php"><h1><?php echo getSetting('site_name'); ?></h1></a>
                </div>
                <div class="header-right">
                    <?php if(isLoggedIn()): ?>
                        <div class="user-dropdown">
                            <span class="username"><i class="fas fa-user"></i> <?php echo $_SESSION['username']; ?></span>
                            <div class="dropdown-menu">
                                <a href="admin/dashboard.php"><i class="fas fa-tachometer-alt"></i> 后台</a>
                                <a href="logout.php"><i class="fas fa-sign-out-alt"></i> 退出</a>
                            </div>
                        </div>
                    <?php else: ?>
                        <a href="login.php" class="btn btn-outline">登录</a>
                        <a href="register.php" class="btn btn-primary">注册</a>
                    <?php endif; ?>
                </div>
            </div>
            <nav class="main-nav">
                <ul>
                    <li><a href="index.php">首页</a></li>
                    <li><a href="articles.php">文章</a></li>
                    <?php 
                    $categories = getCategories();
                    foreach($categories as $cat): 
                    ?>
                    <li><a href="articles.php?cat=<?php echo $cat['id']; ?>"><?php echo $cat['name']; ?></a></li>
                    <?php endforeach; ?>
                    <li><a href="about.php">关于</a></li>
                    <li><a href="contact.php">联系</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <section class="hero">
        <div class="container">
            <h2>CMS系统</h2>
            <p>内容管理系统</p>
            <a href="articles.php" class="btn btn-primary">浏览文章</a>
        </div>
    </section>

    <section class="features">
        <div class="container">
            <h2>系统特色</h2>
            <div class="features-grid">
                <div class="feature">
                    <i class="fas fa-bolt"></i>
                    <h3>高性能</h3>
                    <p>快速加载</p>
                </div>
                <div class="feature">
                    <i class="fas fa-mobile-alt"></i>
                    <h3>响应式</h3>
                    <p>适配所有设备</p>
                </div>
                <div class="feature">
                    <i class="fas fa-cog"></i>
                    <h3>易管理</h3>
                    <p>简单操作</p>
                </div>
            </div>
        </div>
    </section>

    <section class="posts">
        <div class="container">
            <h2>最新文章</h2>
            <div class="posts-grid">
                <?php 
                $posts = getRecentPosts(6);
                foreach($posts as $post): 
                ?>
                <article class="post-card">
                    <?php if($post['featured_image']): ?>
                    <img src="<?php echo UPLOAD_URL . $post['featured_image']; ?>" alt="<?php echo $post['title']; ?>">
                    <?php endif; ?>
                    <div class="post-content">
                        <h3><a href="article.php?id=<?php echo $post['id']; ?>"><?php echo $post['title']; ?></a></h3>
                        <p><?php echo mb_substr(strip_tags($post['content']), 0, 100); ?>...</p>
                        <div class="post-meta">
                            <span><i class="far fa-calendar"></i> <?php echo timeAgo($post['created_at']); ?></span>
                            <span><i class="far fa-eye"></i> <?php echo $post['views']; ?></span>
                        </div>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <footer class="footer">
        <div class="container">
            <p>&copy; 2024 CMS系统</p>
        </div>
    </footer>
</body>
</html> 
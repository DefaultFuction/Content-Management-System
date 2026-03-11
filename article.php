<?php require_once 'includes/config.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$conn->query("UPDATE articles SET views=views+1 WHERE id=$id");

$result = $conn->query("SELECT a.*, u.username, c.name as category_name FROM articles a LEFT JOIN users u ON a.author_id = u.id LEFT JOIN categories c ON a.category_id = c.id WHERE a.id=$id");
$article = $result->fetch_assoc();

if(!$article) {
    header('Location: articles.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $article['title']; ?></title>
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

    <div class="page-header">
        <div class="container">
            <h1><?php echo $article['title']; ?></h1>
        </div>
    </div>

    <main class="main">
        <div class="container">
            <article class="post-full">
                <?php if($article['featured_image']): ?>
                <img src="<?php echo UPLOAD_URL . $article['featured_image']; ?>" alt="<?php echo $article['title']; ?>">
                <?php endif; ?>
                
                <div class="post-meta">
                    <span><i class="far fa-folder"></i> <?php echo $article['category_name']; ?></span>
                    <span><i class="far fa-user"></i> <?php echo $article['username']; ?></span>
                    <span><i class="far fa-calendar"></i> <?php echo date('Y-m-d', strtotime($article['created_at'])); ?></span>
                    <span><i class="far fa-eye"></i> <?php echo $article['views']; ?></span>
                </div>
                
                <div class="post-content">
                    <?php echo nl2br($article['content']); ?>
                </div>
            </article>
        </div>
    </main>

    <footer class="footer">
        <div class="container">
            <p>&copy; 2024 CMS系统</p>
        </div>
    </footer>
</body>
</html>
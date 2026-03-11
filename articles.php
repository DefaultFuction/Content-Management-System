<?php require_once 'includes/config.php'; 

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$cat = isset($_GET['cat']) ? (int)$_GET['cat'] : 0;
$perPage = getSetting('posts_per_page');

$offset = ($page - 1) * $perPage;
$where = $cat > 0 ? "WHERE category_id=$cat AND status=1" : "WHERE status=1";

$result = $conn->query("SELECT COUNT(*) as total FROM articles $where");
$total = $result->fetch_assoc()['total'];
$totalPages = ceil($total / $perPage);

$sql = "SELECT a.*, c.name as category_name FROM articles a LEFT JOIN categories c ON a.category_id = c.id $where ORDER BY a.created_at DESC LIMIT $offset, $perPage";
$articles = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>文章列表</title>
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
            <h1>文章列表</h1>
        </div>
    </div>

    <main class="main">
        <div class="container">
            <div class="content-wrapper">
                <div class="main-content">
                    <?php while($row = $articles->fetch_assoc()): ?>
                    <article class="post-item">
                        <?php if($row['featured_image']): ?>
                        <img src="<?php echo UPLOAD_URL . $row['featured_image']; ?>" alt="<?php echo $row['title']; ?>">
                        <?php endif; ?>
                        <div class="post-item-content">
                            <h2><a href="article.php?id=<?php echo $row['id']; ?>"><?php echo $row['title']; ?></a></h2>
                            <div class="post-meta">
                                <span><i class="far fa-folder"></i> <?php echo $row['category_name']; ?></span>
                                <span><i class="far fa-calendar"></i> <?php echo date('Y-m-d', strtotime($row['created_at'])); ?></span>
                                <span><i class="far fa-eye"></i> <?php echo $row['views']; ?></span>
                            </div>
                            <p><?php echo $row['excerpt'] ?: mb_substr(strip_tags($row['content']), 0, 200); ?>...</p>
                            <a href="article.php?id=<?php echo $row['id']; ?>" class="read-more">阅读全文</a>
                        </div>
                    </article>
                    <?php endwhile; ?>

                    <?php if($totalPages > 1): ?>
                    <div class="pagination">
                        <?php for($i=1; $i<=$totalPages; $i++): ?>
                        <a href="?page=<?php echo $i; ?><?php echo isset($_GET['cat']) ? '&cat='.$_GET['cat'] : ''; ?>" class="<?php echo $i==$page?'active':''; ?>"><?php echo $i; ?></a>
                        <?php endfor; ?>
                    </div>
                    <?php endif; ?>
                </div>

                <aside class="sidebar">
                    <div class="widget">
                        <h3>分类</h3>
                        <ul>
                            <?php foreach(getCategories() as $cat): ?>
                            <li><a href="articles.php?cat=<?php echo $cat['id']; ?>"><?php echo $cat['name']; ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </aside>
            </div>
        </div>
    </main>

    <footer class="footer">
        <div class="container">
            <p>&copy; 2024 CMS系统</p>
        </div>
    </footer>
</body>
</html>
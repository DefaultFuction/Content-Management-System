<?php require_once '../includes/config.php';

if(!isLoggedIn()) {
    header('Location: ../login.php');
    exit;
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $excerpt = $_POST['excerpt'];
    $category_id = (int)$_POST['category_id'];
    
    $conn->query("UPDATE articles SET title='$title', content='$content', excerpt='$excerpt', category_id=$category_id, updated_at=NOW() WHERE id=$id");
    header('Location: articles.php');
    exit;
}

$article = $conn->query("SELECT * FROM articles WHERE id=$id")->fetch_assoc();
$categories = $conn->query("SELECT * FROM categories ORDER BY name");
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>编辑文章</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
    <div class="admin-container">
        <aside class="sidebar"><aside class="sidebar">
            <div class="sidebar-header">
                <h2>CMS后台</h2>
            </div>
            <nav>
                <ul>
                    <li><a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> 仪表盘</a></li>
                    <li><a href="articles.php"><i class="fas fa-newspaper"></i> 文章管理</a></li>
                    <li><a href="categories.php" style="background: #3498db;"><i class="fas fa-folder"></i> 分类管理</a></li>
                    <li><a href="pages.php"><i class="fas fa-file"></i> 页面管理</a></li>
                    <li><a href="media.php"><i class="fas fa-images"></i> 媒体管理</a></li>
                    <li><a href="users.php"><i class="fas fa-users"></i> 用户管理</a></li>
                    <li><a href="settings.php"><i class="fas fa-cog"></i> 系统设置</a></li>
                    <li><a href="tools.php"><i class="fas fa-wrench"></i> 系统工具</a></li>
                    <li><a href="../logout.php"><i class="fas fa-sign-out-alt"></i> 退出登录</a></li>
                </ul>
            </nav>
        </aside></aside>

        <main class="main">
            <header class="header">
                <h1>编辑文章</h1>
            </header>

            <div class="content">
                <form method="POST" class="form">
                    <div class="form-group">
                        <label>标题</label>
                        <input type="text" name="title" value="<?php echo $article['title']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label>分类</label>
                        <select name="category_id">
                            <?php while($cat = $categories->fetch_assoc()): ?>
                            <option value="<?php echo $cat['id']; ?>" <?php echo $cat['id']==$article['category_id']?'selected':''; ?>><?php echo $cat['name']; ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>摘要</label>
                        <textarea name="excerpt" rows="3"><?php echo $article['excerpt']; ?></textarea>
                    </div>
                    <div class="form-group">
                        <label>内容</label>
                        <textarea name="content" rows="10" required><?php echo $article['content']; ?></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">保存</button>
                    <a href="articles.php" class="btn">返回</a>
                </form>
            </div>
        </main>
    </div>
</body>
</html>
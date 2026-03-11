<?php require_once '../includes/config.php';

if(!isLoggedIn()) {
    header('Location: ../login.php');
    exit;
}

if(isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM articles WHERE id=$id");
    header('Location: articles.php');
    exit;
}

$articles = $conn->query("SELECT a.*, c.name as category_name, u.username FROM articles a LEFT JOIN categories c ON a.category_id = c.id LEFT JOIN users u ON a.author_id = u.id ORDER BY a.created_at DESC");
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>文章管理</title>
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
                    <li><a href="articles.php" style="background: #3498db;"><i class="fas fa-newspaper"></i> 文章管理</a></li>
                    <li><a href="categories.php"><i class="fas fa-folder"></i> 分类管理</a></li>
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
                <h1>文章管理</h1>
                <a href="article_add.php" class="btn btn-primary">新增文章</a>
            </header>

            <div class="content">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>标题</th>
                            <th>分类</th>
                            <th>作者</th>
                            <th>发布时间</th>
                            <th>阅读数</th>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $articles->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo $row['title']; ?></td>
                            <td><?php echo $row['category_name']; ?></td>
                            <td><?php echo $row['username']; ?></td>
                            <td><?php echo date('Y-m-d', strtotime($row['created_at'])); ?></td>
                            <td><?php echo $row['views']; ?></td>
                            <td>
                                <a href="article_edit.php?id=<?php echo $row['id']; ?>" class="btn-small">编辑</a>
                                <a href="?delete=<?php echo $row['id']; ?>" class="btn-small btn-danger" onclick="return confirm('确定删除？')">删除</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>
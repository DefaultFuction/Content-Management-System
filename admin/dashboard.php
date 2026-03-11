<?php require_once '../includes/config.php'
;

if(!isLoggedIn()) {
    header('Location: ../login.php');
    exit;
}

$articles = $conn->query("SELECT COUNT(*) as total FROM articles")->fetch_assoc()['total'];
$pages = $conn->query("SELECT COUNT(*) as total FROM pages")->fetch_assoc()['total'];
$users = $conn->query("SELECT COUNT(*) as total FROM users")->fetch_assoc()['total'];
$media = $conn->query("SELECT COUNT(*) as total FROM media")->fetch_assoc()['total'];
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>仪表盘</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
    <div class="admin-container">
<aside class="sidebar">
            <div class="sidebar-header">
                <h2>CMS后台</h2>
            </div>
            <nav>
                <ul>
                    <li><a href="dashboard.php" style="background: #3498db;"><i class="fas fa-tachometer-alt"></i> 仪表盘</a></li>
                    <li><a href="articles.php"><i class="fas fa-newspaper"></i> 文章管理</a></li>
                    <li><a href="categories.php"><i class="fas fa-folder"></i> 分类管理</a></li>
                    <li><a href="pages.php"><i class="fas fa-file"></i> 页面管理</a></li>
                    <li><a href="media.php"><i class="fas fa-images"></i> 媒体管理</a></li>
                    <li><a href="users.php"><i class="fas fa-users"></i> 用户管理</a></li>
                    <li><a href="settings.php"><i class="fas fa-cog"></i> 系统设置</a></li>
                    <li><a href="tools.php"><i class="fas fa-wrench"></i> 系统工具</a></li>
                    <li><a href="../logout.php"><i class="fas fa-sign-out-alt"></i> 退出登录</a></li>
                </ul>
            </nav>
        </aside>

        <main class="main">
            <header class="header">
                <h1>仪表盘</h1>
                <div class="user"><?php echo $_SESSION['username']; ?></div>
            </header>

            <div class="content">
                <div class="stats">
                    <div class="stat-card">
                        <h3>文章</h3>
                        <p><?php echo $articles; ?></p>
                    </div>
                    <div class="stat-card">
                        <h3>页面</h3>
                        <p><?php echo $pages; ?></p>
                    </div>
                    <div class="stat-card">
                        <h3>用户</h3>
                        <p><?php echo $users; ?></p>
                    </div>
                    <div class="stat-card">
                        <h3>媒体</h3>
                        <p><?php echo $media; ?></p>
                    </div>
                </div>

                <div class="info">
                    <h3>系统信息</h3>
                    <table>
                        <tr><th>PHP版本</th><td><?php echo phpversion(); ?></td></tr>
                        <tr><th>MySQL</th><td><?php echo $conn->server_info; ?></td></tr>
                        <tr><th>服务器</th><td><?php echo $_SERVER['SERVER_SOFTWARE']; ?></td></tr>
                        <tr><th>系统</th><td><?php echo PHP_OS; ?></td></tr>
                    </table>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
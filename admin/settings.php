<?php require_once '../includes/config.php';

if(!isLoggedIn() || $_SESSION['role'] != 'admin') {
    header('Location: dashboard.php');
    exit;
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    foreach($_POST as $key => $value) {
        if($key != 'submit') {
            $conn->query("UPDATE settings SET setting_value='$value' WHERE setting_key='$key'");
        }
    }
    $success = '设置已保存';
}

$settings = $conn->query("SELECT * FROM_settings ORDER BY id");
$site_name = getSetting('site_name');
$site_description = getSetting('site_description');
$posts_per_page = getSetting('posts_per_page');
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>系统设置</title>
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
                    <li><a href="categories.php"><i class="fas fa-folder"></i> 分类管理</a></li>
                    <li><a href="pages.php"><i class="fas fa-file"></i> 页面管理</a></li>
                    <li><a href="media.php"><i class="fas fa-images"></i> 媒体管理</a></li>
                    <li><a href="users.php"><i class="fas fa-users"></i> 用户管理</a></li>
                    <li><a href="settings.php" style="background: #3498db;"><i class="fas fa-cog"></i> 系统设置</a></li>
                    <li><a href="tools.php"><i class="fas fa-wrench"></i> 系统工具</a></li>
                    <li><a href="../logout.php"><i class="fas fa-sign-out-alt"></i> 退出登录</a></li>
                </ul>
            </nav>
        </aside></aside>

        <main class="main">
            <header class="header">
                <h1>系统设置</h1>
            </header>

            <div class="content">
                <?php if(isset($success)): ?>
                <div class="alert" style="background:#d4edda;color:#155724;"><?php echo $success; ?></div>
                <?php endif; ?>

                <form method="POST" class="form">
                    <div class="form-group">
                        <label>网站名称</label>
                        <input type="text" name="site_name" value="<?php echo $site_name; ?>">
                    </div>
                    <div class="form-group">
                        <label>网站描述</label>
                        <textarea name="site_description" rows="3"><?php echo $site_description; ?></textarea>
                    </div>
                    <div class="form-group">
                        <label>每页文章数</label>
                        <input type="number" name="posts_per_page" value="<?php echo $posts_per_page; ?>">
                    </div>
                    <button type="submit" name="submit" class="btn btn-primary">保存设置</button>
                </form>
            </div>
        </main>
    </div>
</body>
</html>
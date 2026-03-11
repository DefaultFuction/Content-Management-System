<?php require_once '../includes/config.php';

if(!isLoggedIn()) {
    header('Location: ../login.php');
    exit;
}

$ping_result = '';
if(isset($_GET['ping'])) {
    $host = $_GET['host'];
    if(strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
        $cmd = "ping -n 4 $host";
    } else {
        $cmd = "ping -c 4 $host";
    }
    $ping_result = shell_exec($cmd);
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>系统工具</title>
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
                    <li><a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> 仪表盘</a></li>
                    <li><a href="articles.php"><i class="fas fa-newspaper"></i> 文章管理</a></li>
                    <li><a href="categories.php"><i class="fas fa-folder"></i> 分类管理</a></li>
                    <li><a href="pages.php"><i class="fas fa-file"></i> 页面管理</a></li>
                    <li><a href="media.php"><i class="fas fa-images"></i> 媒体管理</a></li>
                    <li><a href="users.php"><i class="fas fa-users"></i> 用户管理</a></li>
                    <li><a href="settings.php"><i class="fas fa-cog"></i> 系统设置</a></li>
                    <li><a href="tools.php" style="background: #3498db;"><i class="fas fa-wrench"></i> 系统工具</a></li>
                    <li><a href="../logout.php"><i class="fas fa-sign-out-alt"></i> 退出登录</a></li>
                </ul>
            </nav>
        </aside>

        <main class="main">
            <header class="header">
                <h1>系统工具</h1>
            </header>

            <div class="content">
                <div class="tool-box">
                    <h3>Ping测试</h3>
                    <form method="GET">
                        <input type="text" name="host" value="<?php echo isset($_GET['host'])?$_GET['host']:'localhost'; ?>">
                        <button type="submit" name="ping" value="1" class="btn btn-primary">执行</button>
                    </form>
                    
                    <?php if($ping_result): ?>
                    <div class="result-box">
                        <pre><?php echo $ping_result; ?></pre>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
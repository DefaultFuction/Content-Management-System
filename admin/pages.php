<?php 
require_once '../includes/config.php';
;

if(!isLoggedIn()) {
    header('Location: ../login.php');
    exit;
}

if(isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM pages WHERE id=$id");
    header('Location: pages.php');
    exit;
}

$pages = $conn->query("SELECT * FROM pages ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>页面管理 - 后台管理</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
    <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }
    body {
        font-family: Arial, sans-serif;
        background: #f0f2f5;
    }
    .admin-container {
        display: flex;
        min-height: 100vh;
    }
    .sidebar {
        width: 250px;
        background: #2c3e50;
        color: white;
        position: fixed;
        height: 100vh;
        overflow-y: auto;
    }
    .sidebar-header {
        padding: 20px;
        text-align: center;
        border-bottom: 1px solid #34495e;
    }
    .sidebar-header h2 {
        color: white;
        font-size: 20px;
    }
    .sidebar nav ul {
        list-style: none;
        padding: 20px 0;
    }
    .sidebar nav li a {
        display: block;
        padding: 12px 20px;
        color: #ecf0f1;
        text-decoration: none;
        transition: all 0.3s;
    }
    .sidebar nav li a i {
        margin-right: 10px;
        width: 20px;
    }
    .sidebar nav li a:hover {
        background: #3498db;
        padding-left: 30px;
    }
    .main {
        flex: 1;
        margin-left: 250px;
    }
    .header {
        background: white;
        padding: 20px 30px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .header h1 {
        font-size: 24px;
        color: #2c3e50;
    }
    .user {
        padding: 8px 15px;
        background: #f8f9fa;
        border-radius: 5px;
        color: #2c3e50;
    }
    .user i {
        margin-right: 5px;
        color: #3498db;
    }
    .content {
        padding: 30px;
    }
    .btn {
        display: inline-block;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        text-decoration: none;
        font-size: 14px;
        transition: all 0.3s;
    }
    .btn-primary {
        background: #3498db;
        color: white;
    }
    .btn-primary:hover {
        background: #2980b9;
    }
    .btn-small {
        padding: 5px 10px;
        font-size: 12px;
        border-radius: 3px;
        text-decoration: none;
        margin: 0 2px;
    }
    .btn-edit {
        background: #3498db;
        color: white;
    }
    .btn-delete {
        background: #e74c3c;
        color: white;
    }
    .table {
        width: 100%;
        background: white;
        border-radius: 5px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        margin-top: 20px;
    }
    .table th {
        background: #f8f9fa;
        padding: 15px;
        text-align: left;
        font-weight: bold;
        color: #2c3e50;
        border-bottom: 2px solid #3498db;
    }
    .table td {
        padding: 12px 15px;
        border-bottom: 1px solid #eee;
    }
    .table tr:hover {
        background: #f5f5f5;
    }
    .header-actions {
        display: flex;
        gap: 10px;
        align-items: center;
    }
    </style>
</head>
<body>
    <div class="admin-container">
        <!-- 完整侧边栏 -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <h2>CMS后台</h2>
            </div>
            <nav>
                <ul>
                    <li><a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> 仪表盘</a></li>
                    <li><a href="articles.php"><i class="fas fa-newspaper"></i> 文章管理</a></li>
                    <li><a href="categories.php"><i class="fas fa-folder"></i> 分类管理</a></li>
                    <li><a href="pages.php" style="background: #3498db;"><i class="fas fa-file"></i> 页面管理</a></li>
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
                <h1>页面管理</h1>
                <div class="header-actions">
                    <div class="user">
                        <i class="fas fa-user"></i> <?php echo $_SESSION['username']; ?>
                    </div>
                    <a href="page_edit.php" class="btn btn-primary"><i class="fas fa-plus"></i> 新增页面</a>
                </div>
            </header>

            <div class="content">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>标题</th>
                            <th>别名</th>
                            <th>模板</th>
                            <th>更新时间</th>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if($pages && $pages->num_rows > 0): ?>
                            <?php while($row = $pages->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo $row['title']; ?></td>
                                <td><?php echo $row['slug']; ?></td>
                                <td><?php echo $row['template']; ?></td>
                                <td><?php echo date('Y-m-d', strtotime($row['updated_at'] ?: $row['created_at'])); ?></td>
                                <td>
                                    <a href="page_edit.php?id=<?php echo $row['id']; ?>" class="btn-small btn-edit"><i class="fas fa-edit"></i> 编辑</a>
                                    <a href="?delete=<?php echo $row['id']; ?>" class="btn-small btn-delete" onclick="return confirm('确定删除这个页面吗？')"><i class="fas fa-trash"></i> 删除</a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" style="text-align: center; padding: 30px;">暂无页面</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>
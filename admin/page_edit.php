<?php 
require_once '../includes/config.php';
;

if(!isLoggedIn()) {
    header('Location: ../login.php');
    exit;
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $slug = $_POST['slug'];
    $template = $_POST['template'];
    
    if($id > 0) {
        $conn->query("UPDATE pages SET title='$title', content='$content', slug='$slug', template='$template', updated_at=NOW() WHERE id=$id");
    } else {
        $conn->query("INSERT INTO pages (title, content, slug, template, created_at) VALUES ('$title', '$content', '$slug', '$template', NOW())");
    }
    header('Location: pages.php');
    exit;
}

if($id > 0) {
    $result = $conn->query("SELECT * FROM pages WHERE id=$id");
    $page = $result->fetch_assoc();
} else {
    $page = ['title'=>'', 'content'=>'', 'slug'=>'', 'template'=>'default'];
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $id>0?'编辑':'新增'; ?>页面 - 后台管理</title>
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
    }
    .sidebar-header {
        padding: 20px;
        text-align: center;
        border-bottom: 1px solid #34495e;
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
    }
    .sidebar nav li a i {
        margin-right: 10px;
        width: 20px;
    }
    .sidebar nav li a:hover {
        background: #3498db;
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
    }
    .content {
        padding: 30px;
    }
    .form {
        background: white;
        padding: 30px;
        border-radius: 5px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        max-width: 800px;
    }
    .form-group {
        margin-bottom: 20px;
    }
    .form-group label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
        color: #2c3e50;
    }
    .form-group input,
    .form-group textarea,
    .form-group select {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 14px;
    }
    .form-group textarea {
        resize: vertical;
        min-height: 300px;
    }
    .btn {
        display: inline-block;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        text-decoration: none;
        font-size: 14px;
        margin-right: 10px;
    }
    .btn-primary {
        background: #3498db;
        color: white;
    }
    .btn-secondary {
        background: #95a5a6;
        color: white;
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
                <h1><?php echo $id>0?'编辑':'新增'; ?>页面</h1>
                <div class="user">
                    <i class="fas fa-user"></i> <?php echo $_SESSION['username']; ?>
                </div>
            </header>

            <div class="content">
                <form method="POST" class="form">
                    <div class="form-group">
                        <label>页面标题</label>
                        <input type="text" name="title" value="<?php echo $page['title']; ?>" required placeholder="请输入页面标题">
                    </div>
                    
                    <div class="form-group">
                        <label>页面别名 (URL中显示)</label>
                        <input type="text" name="slug" value="<?php echo $page['slug']; ?>" required placeholder="例如: about, contact">
                    </div>
                    
                    <div class="form-group">
                        <label>模板</label>
                        <select name="template">
                            <option value="default" <?php echo $page['template']=='default'?'selected':''; ?>>默认模板</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>页面内容</label>
                        <textarea name="content" required placeholder="请输入页面内容..."><?php echo $page['content']; ?></textarea>
                    </div>
                    
                    <div>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> 保存页面</button>
                        <a href="pages.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> 返回列表</a>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
</html>
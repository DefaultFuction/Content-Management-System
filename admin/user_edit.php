<?php require_once '../includes/config.php';

if(!isLoggedIn() || $_SESSION['role'] != 'admin') {
    header('Location: dashboard.php');
    exit;
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $fullname = $_POST['fullname'];
    $role = $_POST['role'];
    $status = (int)$_POST['status'];
    
    if($id > 0) {
        $conn->query("UPDATE users SET username='$username', email='$email', fullname='$fullname', role='$role', status=$status, updated_at=NOW() WHERE id=$id");
        if($_POST['password']) {
            $password = md5($_POST['password']);
            $conn->query("UPDATE users SET password='$password' WHERE id=$id");
        }
    } else {
        $password = md5($_POST['password']);
        $conn->query("INSERT INTO users (username, password, email, fullname, role, status, created_at) VALUES ('$username', '$password', '$email', '$fullname', '$role', $status, NOW())");
    }
    header('Location: users.php');
    exit;
}

if($id > 0) {
    $user = $conn->query("SELECT * FROM users WHERE id=$id")->fetch_assoc();
} else {
    $user = ['username'=>'', 'email'=>'', 'fullname'=>'', 'role'=>'user', 'status'=>1];
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $id>0?'编辑':'新增'; ?>用户</title>
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
                    <li><a href="users.php" style="background: #3498db;"><i class="fas fa-users"></i> 用户管理</a></li>
                    <li><a href="settings.php"><i class="fas fa-cog"></i> 系统设置</a></li>
                    <li><a href="tools.php"><i class="fas fa-wrench"></i> 系统工具</a></li>
                    <li><a href="../logout.php"><i class="fas fa-sign-out-alt"></i> 退出登录</a></li>
                </ul>
            </nav>
        </aside></aside>

        <main class="main">
            <header class="header">
                <h1><?php echo $id>0?'编辑':'新增'; ?>用户</h1>
            </header>

            <div class="content">
                <form method="POST" class="form">
                    <div class="form-group">
                        <label>用户名</label>
                        <input type="text" name="username" value="<?php echo $user['username']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label>邮箱</label>
                        <input type="email" name="email" value="<?php echo $user['email']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label>姓名</label>
                        <input type="text" name="fullname" value="<?php echo $user['fullname']; ?>">
                    </div>
                    <div class="form-group">
                        <label>密码</label>
                        <input type="password" name="password" placeholder="<?php echo $id>0?'留空则不修改':'请输入密码'; ?>" <?php echo $id>0?'':'required'; ?>>
                    </div>
                    <div class="form-group">
                        <label>角色</label>
                        <select name="role">
                            <option value="admin" <?php echo $user['role']=='admin'?'selected':''; ?>>管理员</option>
                            <option value="editor" <?php echo $user['role']=='editor'?'selected':''; ?>>编辑</option>
                            <option value="user" <?php echo $user['role']=='user'?'selected':''; ?>>普通用户</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>状态</label>
                        <select name="status">
                            <option value="1" <?php echo $user['status']==1?'selected':''; ?>>正常</option>
                            <option value="0" <?php echo $user['status']==0?'selected':''; ?>>禁用</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">保存</button>
                    <a href="users.php" class="btn">返回</a>
                </form>
            </div>
        </main>
    </div>
</body>
</html>
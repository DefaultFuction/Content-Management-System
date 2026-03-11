<?php require_once 'includes/config.php';

if(isLoggedIn()) {
    redirect('index.php');
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = md5($_POST['password']);
    $fullname = $_POST['fullname'];
    
    $check = $conn->query("SELECT id FROM users WHERE username='$username' OR email='$email'");
    if($check->num_rows > 0) {
        $error = '用户名或邮箱已存在';
    } else {
        $conn->query("INSERT INTO users (username, password, email, fullname, role, created_at) VALUES ('$username', '$password', '$email', '$fullname', 'user', NOW())");
        $success = '注册成功，请登录';
    }
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>注册</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="auth-page">
    <div class="auth-container">
        <div class="auth-box">
            <h2>注册</h2>
            <?php if(isset($error)): ?>
            <div class="alert"><?php echo $error; ?></div>
            <?php endif; ?>
            <?php if(isset($success)): ?>
            <div class="alert" style="background:#d4edda;color:#155724;"><?php echo $success; ?></div>
            <?php endif; ?>
            <form method="POST">
                <div class="form-group">
                    <input type="text" name="username" placeholder="用户名" required>
                </div>
                <div class="form-group">
                    <input type="email" name="email" placeholder="邮箱" required>
                </div>
                <div class="form-group">
                    <input type="text" name="fullname" placeholder="姓名">
                </div>
                <div class="form-group">
                    <input type="password" name="password" placeholder="密码" required>
                </div>
                <button type="submit" class="btn btn-primary btn-block">注册</button>
            </form>
            <p><a href="login.php">已有账号？登录</a></p>
        </div>
    </div>
</body>
</html>
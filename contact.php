<?php require_once 'includes/config.php';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];
    
    $to = 'admin@cms.com';
    $subject = "联系表单: $name";
    $body = "姓名：$name\n邮箱：$email\n\n留言：\n$message";
    mail($to, $subject, $body);
    
    $success = '留言已发送';
}

$result = $conn->query("SELECT * FROM pages WHERE slug='contact'");
$page = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>联系我们</title>
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
            <h1>联系我们</h1>
        </div>
    </div>

    <main class="main">
        <div class="container">
            <div class="contact-wrapper">
                <div class="contact-info">
                    <h2>联系方式</h2>
                    <?php echo $page['content']; ?>
                </div>
                
                <div class="contact-form">
                    <h2>发送留言</h2>
                    <?php if(isset($success)): ?>
                    <div class="alert"><?php echo $success; ?></div>
                    <?php endif; ?>
                    
                    <form method="POST">
                        <div class="form-group">
                            <input type="text" name="name" placeholder="姓名" required>
                        </div>
                        <div class="form-group">
                            <input type="email" name="email" placeholder="邮箱" required>
                        </div>
                        <div class="form-group">
                            <textarea name="message" placeholder="留言" rows="5" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">发送</button>
                    </form>
                </div>
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
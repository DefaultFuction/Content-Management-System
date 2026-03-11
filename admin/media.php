<?php require_once '../includes/config.php';

if(!isLoggedIn()) {
    header('Location: ../login.php');
    exit;
}

$uploadDir = '../uploads/';
$imageDir = $uploadDir . 'images/';
$fileDir = $uploadDir . 'files/';

foreach([$uploadDir, $imageDir, $fileDir] as $dir) {
    if(!is_dir($dir)) mkdir($dir, 0777, true);
}

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['file'])) {
    $file = $_FILES['file'];
    $filename = time() . '_' . $file['name'];
    $filetype = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    
    $imageTypes = ['jpg', 'jpeg', 'png', 'gif'];
    $targetDir = in_array($filetype, $imageTypes) ? $imageDir : $fileDir;
    $targetFile = $targetDir . $filename;
    
    if(move_uploaded_file($file['tmp_name'], $targetFile)) {
        $relativePath = str_replace($uploadDir, '', $targetFile);
        $conn->query("INSERT INTO media (filename, filepath, filesize, filetype, uploaded_by, created_at) VALUES ('$filename', '$relativePath', {$file['size']}, '$filetype', {$_SESSION['user_id']}, NOW())");
        header('Location: media.php');
        exit;
    }
}

if(isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $result = $conn->query("SELECT filepath FROM media WHERE id=$id");
    if($row = $result->fetch_assoc()) {
        $filepath = $uploadDir . $row['filepath'];
        if(file_exists($filepath)) unlink($filepath);
        $conn->query("DELETE FROM media WHERE id=$id");
    }
    header('Location: media.php');
    exit;
}

if(isset($_GET['read'])) {
    $file = $uploadDir . $_GET['read'];
    if(file_exists($file)) {
        if(strpos($_GET['read'], 'images/') === 0) {
            header('Content-Type: ' . mime_content_type($file));
        } else {
            header('Content-Type: text/plain');
        }
        readfile($file);
        exit;
    }
}

$media = $conn->query("SELECT m.*, u.username FROM media m LEFT JOIN users u ON m.uploaded_by = u.id ORDER BY m.created_at DESC");
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>媒体管理</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
    <style>
    .media-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 20px;
        margin-top: 20px;
    }
    .media-item {
        background: white;
        border-radius: 5px;
        overflow: hidden;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    .media-preview {
        height: 150px;
        background: #f5f5f5;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .media-preview img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .media-preview i {
        font-size: 48px;
        color: #999;
    }
    .media-info {
        padding: 10px;
    }
    .media-info p {
        margin: 2px 0;
        font-size: 12px;
    }
    .media-actions {
        padding: 10px;
        border-top: 1px solid #eee;
        display: flex;
        gap: 5px;
    }
    .upload-area {
        text-align: center;
        padding: 40px;
        background: #f8f9fa;
        border-radius: 5px;
        margin-bottom: 20px;
    }
    </style>
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
                    <li><a href="media.php" style="background: #3498db;"><i class="fas fa-images"></i> 媒体管理</a></li>
                    <li><a href="users.php"><i class="fas fa-users"></i> 用户管理</a></li>
                    <li><a href="settings.php"><i class="fas fa-cog"></i> 系统设置</a></li>
                    <li><a href="tools.php"><i class="fas fa-wrench"></i> 系统工具</a></li>
                    <li><a href="../logout.php"><i class="fas fa-sign-out-alt"></i> 退出登录</a></li>
                </ul>
            </nav>
        </aside>
        <main class="main">
            <header class="header">
                <h1>媒体管理</h1>
            </header>

            <div class="content">
                <div class="upload-area">
                    <form method="POST" enctype="multipart/form-data">
                        <input type="file" name="file" id="fileInput" style="display:none;" onchange="this.form.submit()">
                        <button type="button" onclick="document.getElementById('fileInput').click()" class="btn btn-primary">
                            <i class="fas fa-upload"></i> 选择文件上传
                        </button>
                    </form>
                </div>

                <div class="media-grid">
                    <?php while($row = $media->fetch_assoc()): 
                        $isImage = in_array($row['filetype'], ['jpg','jpeg','png','gif']);
                        $fileUrl = '../uploads/' . $row['filepath'];
                    ?>
                    <div class="media-item">
                        <div class="media-preview">
                            <?php if($isImage): ?>
                                <img src="<?php echo $fileUrl; ?>" alt="<?php echo $row['filename']; ?>">
                            <?php else: ?>
                                <i class="fas fa-file"></i>
                            <?php endif; ?>
                        </div>
                        <div class="media-info">
                            <p><?php echo $row['filename']; ?></p>
                            <p><?php echo formatFileSize($row['filesize']); ?></p>
                            <p>上传者: <?php echo $row['username']; ?></p>
                        </div>
                        <div class="media-actions">
                            <a href="?read=<?php echo urlencode($row['filepath']); ?>" target="_blank" class="btn-small">查看</a>
                            <a href="?delete=<?php echo $row['id']; ?>" class="btn-small btn-danger" onclick="return confirm('确定删除？')">删除</a>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
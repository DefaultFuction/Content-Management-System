<?php
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function redirect($url) {
    header("Location: $url");
    exit;
}

function timeAgo($datetime) {
    $time = strtotime($datetime);
    $now = time();
    $diff = $now - $time;
    
    if ($diff < 60) return '刚刚';
    if ($diff < 3600) return floor($diff/60).'分钟前';
    if ($diff < 86400) return floor($diff/3600).'小时前';
    if ($diff < 2592000) return floor($diff/86400).'天前';
    return date('Y-m-d', $time);
}

function getSetting($key) {
    global $conn;
    $result = $conn->query("SELECT setting_value FROM settings WHERE setting_key='$key'");
    if ($result->num_rows > 0) {
        return $result->fetch_assoc()['setting_value'];
    }
    return '';
}

function getCategories() {
    global $conn;
    $result = $conn->query("SELECT * FROM categories ORDER BY sort_order, name");
    $categories = [];
    while($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }
    return $categories;
}

function getRecentPosts($limit = 5) {
    global $conn;
    $result = $conn->query("SELECT a.*, c.name as category_name FROM articles a LEFT JOIN categories c ON a.category_id = c.id WHERE a.status=1 ORDER BY a.created_at DESC LIMIT $limit");
    $posts = [];
    while($row = $result->fetch_assoc()) {
        $posts[] = $row;
    }
    return $posts;
}

function formatFileSize($bytes) {
    if ($bytes >= 1073741824) {
        return number_format($bytes / 1073741824, 2) . ' GB';
    } elseif ($bytes >= 1048576) {
        return number_format($bytes / 1048576, 2) . ' MB';
    } elseif ($bytes >= 1024) {
        return number_format($bytes / 1024, 2) . ' KB';
    } else {
        return $bytes . ' bytes';
    }
}
?>
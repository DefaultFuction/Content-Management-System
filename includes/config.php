<?php
session_start();
date_default_timezone_set('Asia/Shanghai');

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '123');
define('DB_NAME', 'cms_system');
define('SITE_URL', 'http://localhost/');
define('UPLOAD_PATH', dirname(__DIR__) . '/uploads/');
define('UPLOAD_URL', SITE_URL . '/uploads/');

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($conn->connect_error) {
    die("数据库连接失败");
}
$conn->set_charset("utf8mb4");
require_once 'includes/functions.php'
?>
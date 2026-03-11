CREATE DATABASE IF NOT EXISTS cms_system;
USE cms_system;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(32) NOT NULL,
    email VARCHAR(100),
    fullname VARCHAR(100),
    avatar VARCHAR(255),
    role ENUM('admin','editor','user') DEFAULT 'user',
    status TINYINT DEFAULT 1,
    created_at DATETIME,
    updated_at DATETIME
);

CREATE TABLE articles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    content TEXT,
    excerpt TEXT,
    featured_image VARCHAR(255),
    category_id INT,
    author_id INT,
    views INT DEFAULT 0,
    status TINYINT DEFAULT 1,
    created_at DATETIME,
    updated_at DATETIME
);

CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    slug VARCHAR(50) UNIQUE,
    description TEXT,
    parent_id INT DEFAULT 0,
    sort_order INT DEFAULT 0,
    created_at DATETIME
);

CREATE TABLE pages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    content TEXT,
    slug VARCHAR(100) UNIQUE,
    template VARCHAR(50) DEFAULT 'default',
    status TINYINT DEFAULT 1,
    created_at DATETIME,
    updated_at DATETIME
);

CREATE TABLE media (
    id INT AUTO_INCREMENT PRIMARY KEY,
    filename VARCHAR(255) NOT NULL,
    filepath VARCHAR(255) NOT NULL,
    filesize INT,
    filetype VARCHAR(50),
    uploaded_by INT,
    created_at DATETIME
);

CREATE TABLE settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) UNIQUE,
    setting_value TEXT
);

INSERT INTO users (username, password, email, fullname, role, created_at) VALUES 
('admin', MD5('admin123'), 'admin@cms.com', '管理员', 'admin', NOW()),
('editor', MD5('editor123'), 'editor@cms.com', '编辑', 'editor', NOW());

INSERT INTO categories (name, slug, description, created_at) VALUES 
('新闻', 'news', '新闻动态', NOW()),
('产品', 'products', '产品中心', NOW()),
('教程', 'tutorials', '技术教程', NOW());

INSERT INTO pages (title, content, slug, created_at) VALUES 
('关于我们', '<h2>关于我们</h2><p>这是一个CMS系统。</p>', 'about', NOW()),
('联系我们', '<h2>联系我们</h2><p>电话：12345678<br>邮箱：info@cms.com</p>', 'contact', NOW());

INSERT INTO settings (setting_key, setting_value) VALUES 
('site_name', 'CMS系统'),
('site_description', '内容管理系统'),
('posts_per_page', '10');
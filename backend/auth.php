<?php
// auth.php - регистрация, вход, выход
require_once 'db.php';

session_start();

// ========== РЕГИСТРАЦИЯ ==========
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'register') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    $errors = [];
    
    if (empty($name)) $errors[] = 'Имя обязательно';
    if (empty($email)) $errors[] = 'Email обязателен';
    if (empty($password)) $errors[] = 'Пароль обязателен';
    if (strlen($password) < 6) $errors[] = 'Пароль должен быть минимум 6 символов';
    
    if (empty($errors)) {
        $pdo = getConnection();
        
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        
        if ($stmt->fetch()) {
            $errors[] = 'Пользователь с таким email уже существует';
        } else {
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (name, email, password_hash) VALUES (?, ?, ?)");
            $stmt->execute([$name, $email, $passwordHash]);
            
            $_SESSION['user_id'] = $pdo->lastInsertId();
            $_SESSION['user_name'] = $name;
            
            header('Location: ../frontend/index.php');
            exit;
        }
    }
    
    $_SESSION['errors'] = $errors;
    header('Location: ../frontend/register.php');
    exit;
}

// ========== ВХОД ==========
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'login') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    $errors = [];
    
    if (empty($email)) $errors[] = 'Email обязателен';
    if (empty($password)) $errors[] = 'Пароль обязателен';
    
    if (empty($errors)) {
        $pdo = getConnection();
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            header('Location: ../frontend/index.php');
            exit;
        } else {
            $errors[] = 'Неверный email или пароль';
        }
    }
    
    $_SESSION['errors'] = $errors;
    header('Location: ../frontend/login.php');
    exit;
}

// ========== ВЫХОД ==========
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_destroy();
    header('Location: ../frontend/login.php');
    exit;
}
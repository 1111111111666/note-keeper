<?php
// notes.php - работа с заметками и тегами
require_once 'db.php';
require_once 'functions.php';
require_once 'CSRF.php';  // Подключаем CSRF-защиту

session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../frontend/login.php');
    exit;
}

// ========== CSRF-ЗАЩИТА ДЛЯ ВСЕХ POST-ЗАПРОСОВ ==========
// Проверяем токен для всех POST-запросов
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['csrf_token'] ?? null;
    
    if (!CSRF::validateToken($token)) {
        // Токен неверный или отсутствует - CSRF-атака!
        http_response_code(403);
        die('Ошибка CSRF: неверный или отсутствующий токен. Попробуйте обновить страницу и повторить действие.');
    }
    
    // Токен верный, можно удалить его (одноразовый)
    CSRF::clearToken();
}

$userId = $_SESSION['user_id'];
$action = $_POST['action'] ?? $_GET['action'] ?? '';
$pdo = getConnection();

// ========== СОЗДАТЬ ЗАМЕТКУ (с тегами) ==========
if ($action === 'create') {
    $title = $_POST['title'] ?? '';
    $body = $_POST['body'] ?? '';
    $tagsString = $_POST['tags'] ?? '';
    
    // Создаём заметку
    $noteId = createNote($pdo, $userId, $title, $body);
    
    // Добавляем теги
    if ($noteId && !empty($tagsString)) {
        $tags = array_map('trim', explode(',', $tagsString));
        foreach ($tags as $tagName) {
            if ($tagName !== '') {
                addTagToNote($pdo, $userId, $noteId, $tagName);
            }
        }
    }
    
    header('Location: ../frontend/index.php');
    exit;
}

// ========== РЕДАКТИРОВАТЬ ЗАМЕТКУ (с тегами) ==========
if ($action === 'update') {
    $id = $_POST['id'] ?? 0;
    $title = $_POST['title'] ?? '';
    $body = $_POST['body'] ?? '';
    $tagsString = $_POST['tags'] ?? '';
    
    // Обновляем заметку
    updateNote($pdo, $id, $userId, $title, $body);
    
    // Удаляем старые теги
    removeAllTagsFromNote($pdo, $id);
    
    // Добавляем новые теги
    if (!empty($tagsString)) {
        $tags = array_map('trim', explode(',', $tagsString));
        foreach ($tags as $tagName) {
            if ($tagName !== '') {
                addTagToNote($pdo, $userId, $id, $tagName);
            }
        }
    }
    
    header('Location: ../frontend/index.php');
    exit;
}

// ========== УДАЛИТЬ ЗАМЕТКУ ==========
if ($action === 'delete') {
    $id = $_GET['id'] ?? 0;
    deleteNote($pdo, $id, $userId);
    header('Location: ../frontend/index.php');
    exit;
}

// ========== ЗАКРЕПИТЬ/ОТКРЕПИТЬ ==========
if ($action === 'toggle_pin') {
    $id = $_GET['id'] ?? 0;
    togglePin($pdo, $id, $userId);
    header('Location: ../frontend/index.php');
    exit;
}

header('Location: ../frontend/index.php');
<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require_once '../backend/db.php';
require_once '../backend/functions.php';
require_once '../backend/CSRF.php';

$pdo = getConnection();
$userId = $_SESSION['user_id'];
$notes = getNotesByUser($pdo, $userId);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Мои заметки</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="card">
        <div class="header">
            <h1>📝 Мои заметки</h1>
            <a href="../backend/auth.php?action=logout" class="logout">🚪 Выйти</a>
        </div>
        
        <p class="welcome">Привет, <?= htmlspecialchars($_SESSION['user_name']) ?>!</p>
        
        <!-- Форма создания заметки с тегами + CSRF-токен -->
        <div class="note-form">
            <h3>➕ Новая заметка</h3>
            <form method="POST" action="../backend/notes.php">
                <?= CSRF::getTokenField() ?>  <!-- ← CSRF-токен -->
                <input type="hidden" name="action" value="create">
                <input type="text" name="title" placeholder="Заголовок" required>
                <textarea name="body" placeholder="Текст заметки..." rows="3"></textarea>
                <input type="text" name="tags" placeholder="Теги (через запятую, например: работа, личное, идеи)" class="tags-input">
                <button type="submit">Создать</button>
            </form>
        </div>
        
        <hr>
        
        <!-- Список заметок -->
        <div class="notes-list">
            <?php if (empty($notes)): ?>
                <p class="empty">✨ У вас пока нет заметок. Создайте первую!</p>
            <?php endif; ?>
            
            <?php foreach ($notes as $note): ?>
                <div class="note <?= $note['is_pinned'] ? 'pinned' : '' ?>">
                    <div class="note-header">
                        <h3><?= htmlspecialchars($note['title']) ?></h3>
                        <div class="note-actions">
                            <a href="../backend/notes.php?action=toggle_pin&id=<?= $note['id'] ?>" class="pin-btn">
                                <?= $note['is_pinned'] ? '📌' : '📍' ?>
                            </a>
                            <button onclick="openEditModal(<?= $note['id'] ?>, '<?= htmlspecialchars($note['title']) ?>', '<?= htmlspecialchars($note['body']) ?>', '<?= htmlspecialchars($note['tags']) ?>')" class="edit-btn">✏️</button>
                            <a href="../backend/notes.php?action=delete&id=<?= $note['id'] ?>" 
                               class="delete-btn" 
                               onclick="return confirm('Удалить заметку?')">🗑️</a>
                        </div>
                    </div>
                    
                    <div class="note-body">
                        <p><?= nl2br(htmlspecialchars($note['body'])) ?></p>
                    </div>
                    
                    <!-- Теги -->
                    <?php if (!empty($note['tags'])): ?>
                        <div class="note-tags">
                            🏷️ 
                            <?php 
                            $tags = explode(', ', $note['tags']);
                            foreach ($tags as $tag): 
                            ?>
                                <span class="tag">
                                    <?= htmlspecialchars($tag) ?>
                                </span>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="note-footer">
                        <small>📅 <?= date('d.m.Y H:i', strtotime($note['updated_at'])) ?></small>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    
    <!-- Модальное окно для редактирования с CSRF-токеном -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h3>✏️ Редактировать заметку</h3>
            <form method="POST" action="../backend/notes.php">
                <?= CSRF::getTokenField() ?>  <!-- ← CSRF-токен -->
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="id" id="edit_id">
                <input type="text" name="title" id="edit_title" placeholder="Заголовок" required>
                <textarea name="body" id="edit_body" placeholder="Текст заметки..." rows="5"></textarea>
                <input type="text" name="tags" id="edit_tags" placeholder="Теги (через запятую)" class="tags-input">
                <button type="submit">💾 Сохранить</button>
            </form>
        </div>
    </div>
    
    <script>
        var modal = document.getElementById('editModal');
        var span = document.getElementsByClassName('close')[0];
        
        function openEditModal(id, title, body, tags) {
            document.getElementById('edit_id').value = id;
            document.getElementById('edit_title').value = title;
            document.getElementById('edit_body').value = body;
            document.getElementById('edit_tags').value = tags;
            modal.style.display = 'block';
        }
        
        span.onclick = function() {
            modal.style.display = 'none';
        }
        
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }
    </script>
</body>
</html>
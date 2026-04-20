<?php
// functions.php - полезные функции

function createNote(PDO $pdo, int $userId, string $title, string $body): int {
    $stmt = $pdo->prepare("INSERT INTO notes (user_id, title, body) VALUES (?, ?, ?)");
    $stmt->execute([$userId, $title, $body]);
    return (int)$pdo->lastInsertId();
}

function getNotesByUser(PDO $pdo, int $userId): array {
    $stmt = $pdo->prepare("
        SELECT n.*, 
               COALESCE(
                   (SELECT STRING_AGG(t.name, ', ')
                    FROM note_tags nt
                    JOIN tags t ON t.id = nt.tag_id
                    WHERE nt.note_id = n.id), ''
               ) as tags
        FROM notes n
        WHERE n.user_id = ?
        ORDER BY n.is_pinned DESC, n.updated_at DESC
    ");
    $stmt->execute([$userId]);
    return $stmt->fetchAll();
}

function updateNote(PDO $pdo, int $noteId, int $userId, string $title, string $body): int {
    $stmt = $pdo->prepare("
        UPDATE notes 
        SET title = ?, body = ?, updated_at = CURRENT_TIMESTAMP 
        WHERE id = ? AND user_id = ?
    ");
    $stmt->execute([$title, $body, $noteId, $userId]);
    return $stmt->rowCount();
}

function deleteNote(PDO $pdo, int $noteId, int $userId): int {
    $stmt = $pdo->prepare("DELETE FROM notes WHERE id = ? AND user_id = ?");
    $stmt->execute([$noteId, $userId]);
    return $stmt->rowCount();
}

function togglePin(PDO $pdo, int $noteId, int $userId): int {
    $stmt = $pdo->prepare("
        UPDATE notes 
        SET is_pinned = NOT is_pinned 
        WHERE id = ? AND user_id = ?
    ");
    $stmt->execute([$noteId, $userId]);
    return $stmt->rowCount();
}

// ========== ФУНКЦИИ ДЛЯ ТЕГОВ ==========

function addTagToNote(PDO $pdo, int $userId, int $noteId, string $tagName): void {
    // Найти или создать тег
    $stmt = $pdo->prepare("SELECT id FROM tags WHERE name = ? AND user_id = ?");
    $stmt->execute([$tagName, $userId]);
    $tag = $stmt->fetch();
    
    if (!$tag) {
        $stmt = $pdo->prepare("INSERT INTO tags (name, user_id) VALUES (?, ?)");
        $stmt->execute([$tagName, $userId]);
        $tagId = $pdo->lastInsertId();
    } else {
        $tagId = $tag['id'];
    }
    
  
    $stmt = $pdo->prepare("
        INSERT INTO note_tags (note_id, tag_id) 
        SELECT ?, ? 
        WHERE NOT EXISTS (SELECT 1 FROM note_tags WHERE note_id = ? AND tag_id = ?)
    ");
    $stmt->execute([$noteId, $tagId, $noteId, $tagId]);
}

function removeAllTagsFromNote(PDO $pdo, int $noteId): void {
    $stmt = $pdo->prepare("DELETE FROM note_tags WHERE note_id = ?");
    $stmt->execute([$noteId]);
}
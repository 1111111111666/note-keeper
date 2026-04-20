<?php session_start(); ?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Регистрация - Note Keeper</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="card">
        <h1>📝 Регистрация</h1>
        
        <?php if (isset($_SESSION['errors'])): ?>
            <div class="errors">
                <?php foreach ($_SESSION['errors'] as $error): ?>
                    <p class="error">❌ <?= htmlspecialchars($error) ?></p>
                <?php endforeach; ?>
            </div>
            <?php unset($_SESSION['errors']); ?>
        <?php endif; ?>
        
        <form method="POST" action="../backend/auth.php">
            <input type="hidden" name="action" value="register">
            <input type="text" name="name" placeholder="Имя" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Пароль (минимум 6 символов)" required>
            <button type="submit">Зарегистрироваться</button>
        </form>
        
        <p>Уже есть аккаунт? <a href="login.php">Войти</a></p>
    </div>
</body>
</html>
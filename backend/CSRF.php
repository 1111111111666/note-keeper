<?php
// CSRF.php - защита от межсайтовых поддельных запросов

/**
 * Класс для генерации и проверки CSRF-токенов
 * 
 * Принцип работы:
 * 1. При загрузке формы генерируется уникальный токен
 * 2. Токен сохраняется в сессии и добавляется в форму
 * 3. При отправке формы токен из сессии сравнивается с токеном из формы
 * 4. Если токены не совпадают или отсутствуют - запрос отклоняется
 */
class CSRF {
    
    /**
     * Генерирует новый CSRF-токен и сохраняет его в сессии
     * 
     * Токен создаётся с помощью random_bytes() для криптографической стойкости
     * bin2hex() преобразует бинарные данные в читаемую строку
     * 
     * @return string Сгенерированный токен
     */
    public static function generateToken(): string {
        // Создаём 32 случайных байта (64 символа в hex-формате)
        $token = bin2hex(random_bytes(32));
        
        // Сохраняем токен в сессии
        $_SESSION['csrf_token'] = $token;
        
        return $token;
    }
    
    /**
     * Возвращает HTML-поле для вставки токена в форму
     * 
     * Используется в шаблонах для автоматической вставки скрытого поля
     * 
     * @return string HTML с скрытым полем
     */
    public static function getTokenField(): string {
        $token = self::generateToken();
        return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($token) . '">';
    }
    
    /**
     * Проверяет, что токен из формы соответствует токену в сессии
     * 
     * Вызывается перед обработкой любого POST-запроса
     * 
     * @param string|null $token Токен из формы (обычно из $_POST['csrf_token'])
     * @return bool true если токен валидный, false если нет
     */
    public static function validateToken(?string $token): bool {
        // Проверяем, что токен передан и что он есть в сессии
        if (empty($token) || empty($_SESSION['csrf_token'])) {
            return false;
        }
        
        // Сравниваем токены (используем hash_equals для защиты от timing-атак)
        // hash_equals сравнивает строки за одинаковое время, что предотвращает взлом по времени
        return hash_equals($_SESSION['csrf_token'], $token);
    }
    
    /**
     * Удаляет токен из сессии после проверки
     * 
     * Используется после успешной валидации для одноразовости токена
     */
    public static function clearToken(): void {
        unset($_SESSION['csrf_token']);
    }
}
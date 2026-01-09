<?php
require_once __DIR__ . '/../config/db.php';

class User
{
    public static function findByEmail(string $email): ?array
    {
        $pdo = getDB();
        $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    public static function findById(int $id): ?array
    {
        $pdo = getDB();
        $stmt = $pdo->prepare('SELECT user_id, name, email, role, created_at FROM users WHERE user_id = ?');
        $stmt->execute([$id]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    public static function register(string $name, string $email, string $password, string $role = 'citizen'): bool
    {
        $pdo = getDB();
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $pdo->prepare('INSERT INTO users (name, email, password, role, created_at) VALUES (?, ?, ?, ?, NOW())');
        return $stmt->execute([$name, $email, $hash, $role]);
    }

    public static function authenticate(string $email, string $password): ?array
    {
        $user = self::findByEmail($email);
        if ($user) {
            // Primary: secure hash check
            if (password_verify($password, $user['password'])) {
                unset($user['password']);
                return $user;
            }
            // Fallback: allow direct match in case password was stored as plain text in DB
            if ($password === $user['password']) {
                unset($user['password']);
                return $user;
            }
        }
        return null;
    }
}


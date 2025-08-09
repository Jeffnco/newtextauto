<?php
namespace ContentFactory\Core;

class Auth
{
    public static function check(): bool
    {
        session_start();
        return isset($_SESSION['user_id']);
    }

    public static function requireAuth(): void
    {
        if (!self::check()) {
            header('Location: /auth/login');
            exit;
        }
    }

    public static function login(string $email, string $password): bool
    {
        $db = Database::getInstance();
        $result = $db->makeRequest(
            $db->getTableName('users') . '?filter=(email,eq,' . urlencode($email) . ')'
        );

        if ($result['success'] && !empty($result['data']['list'])) {
            $user = $result['data']['list'][0];
            if (password_verify($password, $user['password_hash'])) {
                session_start();
                $_SESSION['user_id'] = $user['Id'];
                $_SESSION['user_email'] = $user['email'];
                return true;
            }
        }
        return false;
    }

    public static function logout(): void
    {
        session_start();
        session_destroy();
    }

    public static function user(): ?array
    {
        session_start();
        return $_SESSION ?? null;
    }
}
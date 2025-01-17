<?php


namespace App\Core;

use Dotenv\Util\Str;

class SessionManager
{
    public static function start(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
            session_regenerate_id(true);
        }
    }
    public static function set(string $key, $value)
    {
        $_SESSION[$key] = $value;
    }
    public static function regenerateId(bool $deleteOldSession=true){
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        return session_regenerate_id($deleteOldSession);
    }

    public static function get(string $key)
    {
        return $_SESSION[$key] ?? null;
    }
    public static function exists(string $key): bool
    {
        return isset($_SESSION[$key]);
    }
    public static function remove(string $key): void
    {
        unset($_SESSION[$key]);
    }
    public static function destroy(): void
    {
        session_unset();
        session_destroy();
    }
}

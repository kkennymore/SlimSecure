<?php

namespace SlimSecure\Core;

use SlimSecure\Configs\Env;

/**
 * Class Cookie
 * Handles cookie operations.
 */
class Cookie
{

    /**
     * @var string|null The flash message stored in the cookie.
     */
    protected static ?string $flash_message = null;

    /**
     * Set a flash message in the cookie.
     *
     * @param string $message The flash message.
     */
    public static function setFlash(string $message): void
    {
        self::$flash_message = $message;
    }

    /**
     * Check if a flash message exists in the cookie.
     *
     * @return bool Indicates if a flash message exists.
     */
    public static function hasFlash(): bool
    {
        return !is_null(self::$flash_message);
    }

    /**
     * Display the flash message and clear it from the cookie.
     */
    public static function flash(): void
    {
        echo self::$flash_message;
        self::$flash_message = null;
    }

    /**
     * Set a cookie.
     *
     * @param string $key The cookie key.
     * @param string|array $value The cookie value.
     */
    public static function set(string $key, string | array $value): void
    {
        setcookie($key, json_encode($value), time() + (3600 * Env::COOKIE_EXPIRATION_TIME_IN_HOURS), '/');
    }

    /**
     * Get the value of a cookie.
     *
     * @param string $key The cookie key.
     * @return string|array|null The cookie value or null if not found.
     */
    public static function get(string $key): string | array | null
    {
        return isset($_COOKIE[$key]) ? json_decode($_COOKIE[$key], true) : null;
    }

    /**
     * Delete a cookie.
     *
     * @param string $key The cookie key.
     */
    public static function cookieDelete(string $key): void
    {
        if (isset($_COOKIE[$key])) {
            unset($_COOKIE[$key]);
        }
    }
    /*
 * Destroy all cookies.
 *
 * @return bool Indicates if the cookies were destroyed successfully.
 */
    public static function destroyAll()
    {
        // Iterate through existing cookies and destroy each one
        foreach ($_COOKIE as $key => $value) {
            self::cookieDelete($key);
        }

        return true;
    }
}

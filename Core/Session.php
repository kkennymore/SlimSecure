<?php
namespace SlimSecure\Core;
/**
 * Slimez Session Class
 *
 * Author: Oaad Global
 * Developer: Hitek Financials Ltd
 * Year: 2024
 * Developer Contact: contact@tekfinancials.ng, kennethusiobaifo@yahoo.com
 * Project Name: Slimez
 * Description: Slimez.
 */
class Session
{
    protected static $flash_message;

    /**
     * Set a flash message.
     *
     * @param string $message The flash message to be set.
     */
    public static function setFlash($message)
    {
        self::$flash_message = $message;
    }

    /**
     * Check if a flash message exists.
     *
     * @return bool Returns true if a flash message exists, false otherwise.
     */
    public static function hasFlash()
    {
        return !is_null(self::$flash_message);
    }

    /**
     * Display and clear the flash message.
     */
    public static function flash()
    {
        echo self::$flash_message;
        self::$flash_message = null;
    }

    /**
     * Set a session value.
     *
     * @param string $key   The key of the session.
     * @param mixed  $value The value to be stored in the session.
     */
    public static function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Retrieve a session value by its key.
     *
     * @param string $key The key of the session value to be retrieved.
     *
     * @return mixed|null The retrieved session value or null if the key does not exist.
     */
    public static function get($key)
    {
        if (isset($_SESSION[$key])) {
             return $_SESSION[$key];
            return null;
        }
        return null;
    }

    /**
     * Delete a session value by its key.
     *
     * @param string $key The key of the session value to be deleted.
     */
    public static function sessDelete($key)
    {
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }

    /**
     * Destroy the current session.
     */
    public static function destroy()
    {
        session_destroy();
    }
}

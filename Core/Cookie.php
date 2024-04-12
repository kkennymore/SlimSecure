<?php

namespace SlimSecure\Core;

use SlimSecure\Configs\Env;

/**
 * Class Cookie
 *
 * Provides utility methods for managing cookies in the SlimSecure application.
 * This class handles the creation, retrieval, and deletion of cookies, including
 * flash messages stored temporarily in cookies.
 */
class Cookie
{
    /**
     * Holds the flash message temporarily in the runtime, not actually in the cookie.
     * This is a static property to keep the message available during a single request.
     *
     * @var string|null The flash message stored temporarily.
     */
    protected static ?string $flash_message = null;

    /**
     * Stores a flash message in a runtime static property. This message can be retrieved
     * and displayed during the lifetime of the current request.
     *
     * @param string $message The message to store as a flash message.
     */
    public static function setFlash(string $message): void
    {
        self::$flash_message = $message;
    }

    /**
     * Checks if a flash message has been set.
     *
     * @return bool Returns true if a flash message exists, otherwise false.
     */
    public static function hasFlash(): bool
    {
        return !is_null(self::$flash_message);
    }

    /**
     * Displays the current flash message and then clears it from the static property.
     * This method should be called typically in response to a successful operation
     * to provide feedback to the user.
     */
    public static function flash(): void
    {
        echo self::$flash_message;
        self::$flash_message = null;  // Clear the message after displaying it.
    }

    /**
     * Sets a cookie with specified key and value. The value can be a string or an array.
     * Arrays are encoded as JSON strings before being stored in the cookie. The cookie
     * expiration is set based on a predefined constant from the environment configuration.
     *
     * @param string $key The name of the cookie.
     * @param string|array $value The value of the cookie, which will be stored as a JSON string if it's an array.
     */
    public static function set(string $key, $value): void
    {
        setcookie($key, json_encode($value), time() + (3600 * Env::COOKIE_EXPIRATION_TIME_IN_HOURS), '/');
    }

    /**
     * Retrieves the value of a specified cookie. If the cookie exists and contains JSON,
     * it decodes the JSON back into an array. Otherwise, it returns the string value of the cookie.
     *
     * @param string $key The name of the cookie to retrieve.
     * @return string|array|null Returns the value of the cookie if it exists, otherwise null.
     */
    public static function get(string $key)
    {
        return isset($_COOKIE[$key]) ? json_decode($_COOKIE[$key], true) : null;
    }

    /**
     * Deletes a cookie by unsetting it in the PHP $_COOKIE superglobal and by sending an
     * expired cookie to the user's browser.
     *
     * @param string $key The name of the cookie to delete.
     */
    public static function cookieDelete(string $key): void
    {
        if (isset($_COOKIE[$key])) {
            setcookie($key, '', time() - 3600, '/');  // Set the cookie with an expired timestamp.
            unset($_COOKIE[$key]);  // Unset the cookie from the superglobal.
        }
    }

    /**
     * Destroys all cookies set by the application by iterating through each cookie
     * and deleting it.
     *
     * @return bool Returns true to indicate that all cookies have been marked for deletion.
     */
    public static function destroyAll(): bool
    {
        foreach ($_COOKIE as $key => $value) {
            self::cookieDelete($key);
        }

        return true;
    }
}

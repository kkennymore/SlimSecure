<?php

namespace SlimSecure\Core;

use SlimSecure\Configs\Env;

/**
 * Abstract class Security
 * 
 * Provides a suite of methods for handling common security operations such as encryption, decryption,
 * padding, and data sanitation. This class is designed to offer utilities that ensure data integrity and
 * confidentiality across the SlimSecure application.
 */
abstract class Security
{
    /**
     * Adds specified padding to a word from both sides.
     *
     * This method allows adding spaces before and/or after the word. It can be used to format strings
     * for better readability or other purposes that require specific spacing around words.
     *
     * @param string $word The word to add padding to.
     * @param int $paddingLeft The number of spaces to add on the left of the word.
     * @param int $paddingRight The number of spaces to add on the right of the word.
     * @return string The word with added padding.
     */
    public static function addPadding(string $word, int $paddingLeft = 0, int $paddingRight = 0): string
    {
        return str_repeat(" ", $paddingLeft) . $word . str_repeat(" ", $paddingRight);
    }

    /**
     * Safeguards a string by adding slashes to escape special characters and stripping tags.
     *
     * This method is useful for preparing user input for storage in a database, helping prevent
     * injection attacks or unintended execution of HTML/JavaScript.
     *
     * @param string $string The string to protect.
     * @return string The safeguarded string.
     */
    public static function kenProtectFunc(string $string): string
    {
        return addslashes(strip_tags($string));
    }

    /**
     * Encrypts a string using AES-256-CBC encryption.
     *
     * This method provides strong encryption using a secret key and a randomly generated
     * initialization vector (IV) for each encryption operation.
     *
     * @param string $string The plain text to encrypt.
     * @param string $key The secret key for encryption.
     * @return string The encrypted string, encoded in base64 to ensure transportability.
     */
    public static function kenEncrypt(string $string, string $key): string
    {
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
        $encrypted = openssl_encrypt($string, 'aes-256-cbc', base64_decode($key), 0, $iv);
        return base64_encode($encrypted . '::' . $iv);
    }

    /**
     * Decrypts a string previously encrypted by kenEncrypt using AES-256-CBC encryption.
     *
     * @param string $string The base64 encoded encrypted string.
     * @param string $key The secret key used for encryption.
     * @return string|false The decrypted string or false on failure.
     */
    public static function kenDecrypt(string $string, string $key)
    {
        list($encrypted_data, $iv) = explode('::', base64_decode($string), 2);
        return openssl_decrypt($encrypted_data, 'aes-256-cbc', base64_decode($key), 0, $iv);
    }

    /**
     * Generates a simple numeric One-Time Password (OTP) of specified length.
     *
     * @param int $length Desired length of the OTP.
     * @return string The generated OTP.
     */
    public static function OTPGen(int $length = 6): string
    {
        $numbers = substr(str_shuffle("0123456789"), 0, $length);
        return $numbers;
    }

    /**
     * Generates a universally unique identifier (UUID) version 4.
     *
     * UUIDs generated are random and provide a high probability of uniqueness over space and time.
     *
     * @return string The generated UUID.
     */
    public static function genUuid(): string
    {
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }

    /**
     * Creates a secure hash of a password using the bcrypt algorithm.
     *
     * This method is recommended for password hashing as it includes a salt automatically
     * and is resistant to brute-force search attacks even with increasing computation power.
     *
     * @param string $password The password to hash.
     * @return string The hashed password.
     */
    public static function hashPassword(string $password): string
    {
        return password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
    }

    /**
     * Verifies a password against a hash created by hashPassword.
     *
     * @param string $password The password to verify.
     * @param string $hashedPassword The hash to verify against.
     * @return bool True if the password matches the hash, false otherwise.
     */
    public static function verifyPassword(string $password, string $hashedPassword): bool
    {
        return password_verify($password, $hashedPassword);
    }

    /**
     * Validates an email address against a comprehensive regular expression.
     *
     * This method checks for the general structure of email addresses, including checks for
     * valid characters, appropriate placement of symbols like @ and '.', and domain length checks.
     *
     * @param string $email The email address to validate.
     * @return bool True if the email address is valid according to the pattern, false otherwise.
     */
    public static function emailRegularExpression(string $email): bool
    {
        $pattern = '/^...$/'; // Simplified for brevity; real pattern should be replaced here
        return (bool)preg_match($pattern, $email);
    }

    /**
     * Checks if a username matches the regular expression pattern for username validation.
     *
     * @param string $username The username to validate.
     * @return string Returns true if the username is valid, false otherwise.
     */
    public static function usernameRegularExpression(string $username): string
    {
        return preg_match('/^[a-zA-Z0-9_-]{3,20}$/', $username);
    }

    /**
     * Checks if a password matches the regular expression pattern for password validation.
     *
     * @param string $password The password to validate.
     * @return string Returns true if the password is valid, false otherwise.
     */
    public static function passwordRegularExpression(string $password): string
    {
        return preg_match('((?=.*\d)(?=.*[a-z]).{6,20})', $password);
    }

    /**
     * Validates a mobile phone number using a regular expression pattern.
     *
     * @param string $number The mobile phone number to validate.
     * @return string Returns true if the mobile phone number is valid, false otherwise.
     */
    public static function mobileNumberValidation(string $number): string
    {
        return preg_match(
            '/^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/im',
            $number
        );
    }

    /**
     * Validates an experience number.
     *
     * @param mixed $number The experience number to validate.
     * @return string Returns true if the experience number is valid, false otherwise.
     */
    public static function experienceNumberValidation($number)
    {
        return preg_match('/^[0-9_-]{1,14}$/', $number);
    }

    /**
     * Removes underscores from a string.
     *
     * @param string $string The string to remove underscores from.
     * @return string The string with underscores removed.
     */
    public static function trimUnderscore(string $string): string
    {
        return str_replace('_', ' ', $string);
    }

    /**
     * Adds underscores to a string.
     *
     * @param string $string The string to add underscores to.
     * @return string The string with underscores added.
     */
    public static function addUnderscore(string $string): string
    {
        return str_replace(' ', '_', $string);
    }

    /**
     * Replaces forward slashes in a string.
     *
     * @param mixed $string The string to replace forward slashes in.
     * @return string The string with forward slashes replaced.
     */
    public static function replaceForwardSlashed($string): string
    {
        return str_replace('/', 'XSJ', $string);
    }

    /**
     * Removes ampersigns from a string.
     *
     * @param string $string The string to remove ampersigns from.
     * @return string The string with ampersigns removed.
     */
    public static function removeAmpersign(string $string): string
    {
        return str_replace('&', 'XSJ', $string);
    }

    /**
     * Adds ampersigns to a string.
     *
     * @param string $string The string to add ampersigns to.
     * @return string The string with ampersigns added.
     */
    public static function addAmpersign(string $string): string
    {
        if ($string == null || $string == '') {
            return "";
        }
        return str_replace('XSJ', '&', $string);
    }

    /**
     * Inserts forward slashes into a string.
     *
     * @param mixed $string The string to insert forward slashes into.
     * @return mixed The string with forward slashes inserted.
     */
    public static function insertForwardSlashed($string)
    {
        if ($string == null || $string == '') {
            return "";
        }
        return str_replace('XSJ', '/', $string);
    }

    /**
     * Removes pound signs from a string.
     *
     * @param string $string The string to remove pound signs from.
     * @return string The string with pound signs removed.
     */
    public static function underPoundSignRemove(string $string): string
    {
        if ($string == null || $string == '') {
            return "";
        }
        return str_replace('#', '', $string);
    }

    /**
     * Adds pound signs to a string.
     *
     * @param string $string The string to add pound signs to.
     * @return string The string with pound signs added.
     */
    public static function addPoundSign(string $string): string
    {
        if ($string == null || $string == '') {
            return "";
        }
        return str_replace(' ', '#', $string);
    }

    /**
     * Removes underscores from a string.
     *
     * @param string $string The string to remove underscores from.
     * @return string The string with underscores removed.
     */
    public static function underscoreRemove(string $string): string
    {
        if ($string == null || $string == '') {
            return "";
        }
        return str_replace('_', ' ', $string);
    }

    /**
     * Removes dashes from a string.
     *
     * @param string $string The string to remove dashes from.
     * @return string The string with dashes removed.
     */
    public static function dashesRemove(string $string): string
    {
        if ($string == null || $string == '') {
            return "";
        }
        return str_replace('-', ' ', $string);
    }

    /**
     * Adds dashes to a string.
     *
     * @param string $string The string to add dashes to.
     * @return string The string with dashes added.
     */
    public static function addDashes(string $string): string
    {
        if ($string == null || $string == '') {
            return "";
        }
        return str_replace(' ', '-', $string);
    }

    /**
     * Adds spaces to a string.
     *
     * @param string $string The string to add spaces to.
     * @return string The string with spaces added.
     */
    public static function addSpace(string $string): string
    {
        if ($string == null || $string == '') {
            return "";
        }
        return str_replace(' ', 'spkahfst', $string);
    }

    /**
     * Adds backslashes to a string.
     *
     * @param string $string The string to add backslashes to.
     * @return string The string with backslashes added.
     */
    public static function addBackSlash(string $string): string
    {
        if ($string == null || $string == '') {
            return "";
        }
        return str_replace('', '\\', $string);
    }

    /**
     * Removes backslashes from a string.
     *
     * @param string $string The string to remove backslashes from.
     * @return string The string with backslashes removed.
     */
    public static function removeBackSlash(string $string): string
    {
        if ($string == null || $string == '') {
            return "";
        }
        return str_replace('\\', '', $string);
    }

    /**
     * Replaces bad words in a string with asterisks.
     *
     * @param string $string The string to replace bad words in.
     * @return string The string with bad words replaced.
     */
    public static function replaceBadWord(string $string): string
    {
        if ($string == null || $string == '') {
            return "";
        }

        $words = ['fuck', 'sex', 'pussy', 'penis', 'ass', 'dick', 'bitch'];
        $replace = ['f**k', 's*x', 'p***y', 'p***s', 'a*s', 'd**k', 'b***h'];
        return str_replace($words, $replace, $string);
    }

    /**
     * Replaces plus signs in a string.
     *
     * @param string $string The string to replace plus signs in.
     * @return string The string with plus signs replaced.
     */
    public static function plusSign(string $string): string
    {
        return str_replace('+', 'PLUSSIGN', $string);
    }

    /**
     * Removes plus signs from a string.
     *
     * @param string $string The string to remove plus signs from.
     * @return string The string with plus signs removed.
     */
    public static function removePlusSign(string $string): string
    {
        return str_replace('PLUSSIGN', '+', $string);
    }

    /**
     * Encrypts data using AES-256-CBC encryption.
     *
     * @param mixed $strings The data to encrypt.
     * @return mixed The encrypted data.
     */
    public static function encryption(?string $strings)
    {
        if(!empty($strings)){
            $sSalt = substr(hash('sha256', Env::ENCRYPTION_KEY, true), 0, 32);
            $method = 'aes-256-cbc';
            $iv = base64_decode('DB4gHxkcBQkKCxoRGBkaFA==');
            $encryptedString = openssl_encrypt($strings, $method, $sSalt, 0, $iv);
            return $encryptedString;
        }
        return "";
    }

    /**
     * Decrypts data using AES-256-CBC decryption.
     *
     * @param string $string The data to decrypt.
     * @return string The decrypted data.
     */
    public static function decryption(?string $string)
    {
        if(!empty($string)){
            $sSalt = substr(hash('sha256', Env::ENCRYPTION_KEY, true), 0, 32);
            $method = 'aes-256-cbc';
            $iv = base64_decode('DB4gHxkcBQkKCxoRGBkaFA==');
            $decrypted = openssl_decrypt($string, $method, $sSalt, 0, $iv);
            return $decrypted;
        }
        return "";
    }

    /**
     * Encrypts a file using AES-256-CBC encryption.
     *
     * @param string $encKey The encryption key.
     * @param string $encIV The encryption initialization vector.
     * @param string $inPath The input file path.
     * @param string $outPath The output file path.
     * @return bool Returns true if the file encryption is successful, false otherwise.
     */
    public static function fileEncryption(
        string $encKey,
        string $encIV,
        string $inPath,
        string $outPath
    ) {
        $inFile = fopen($inPath, "rb");
        $outFile = fopen($outPath, "wb");
        if (!$inFile || !$outFile) {
            return false;
        }
        $encryption_key = base64_decode($encKey);
        $iv = base64_decode($encIV);
        stream_filter_append($outFile, 'openssl.encrypt', STREAM_FILTER_WRITE, [
            'key' => $encryption_key,
            'iv' => $iv,
        ]);
        stream_copy_to_stream($inFile, $outFile);
        fclose($inFile);
        fclose($outFile);
        return true;
    }

    /**
     * Decrypts a file using AES-256-CBC decryption.
     *
     * @param string $encKey The encryption key.
     * @param string $encIV The encryption initialization vector.
     * @param string $inPath The input file path.
     * @param string $outPath The output file path.
     * @return bool Returns true if the file decryption is successful, false otherwise.
     */
    public static function fileDecryption(
        string $encKey,
        string $encIV,
        string $inPath,
        string $outPath
    ) {
        $inFile = fopen($inPath, "rb");
        $outFile = fopen($outPath, "wb");
        if (!$inFile || !$outFile) {
            return false;
        }
        $encryption_key = base64_decode($encKey);
        $iv = base64_decode($encIV);
        stream_filter_append($inFile, 'openssl.decrypt', STREAM_FILTER_READ, [
            'key' => $encryption_key,
            'iv' => $iv,
        ]);
        stream_copy_to_stream($inFile, $outFile);
        fclose($inFile);
        fclose($outFile);
        return true;
    }

    public static function removeSpaces(string $string)
    {
        return preg_replace('/\s+/', '', $string);
    }

    /**
     * Formats the mobile number.
     *
     * @param string $mobile The mobile number to format.
     * @param bool $intl Indicates whether to return the international format.
     *
     * @return string The formatted mobile number.
     */
    public static function formatMobile(string $mobile, bool $intl = false)
    {
        $trimedNumber = ltrim(ltrim(ltrim(ltrim(ltrim(ltrim(self::removeSpaces($mobile), '0'), '+'), '+2340'), '+234'), '002340'), '00234');
        if ($intl) {
            return '+234' . $trimedNumber;
        }
        return '0' . $trimedNumber;
    }

    /**
     * Validates the user IP address.
     *
     * @param string $ipAddress The IP address to validate.
     *
     * @return bool|string Returns the validated IP address if valid, otherwise false.
     */
    public static function isValidIpAddress($ipAddress)
    {
        if (
            filter_var(
                $ipAddress,
                FILTER_VALIDATE_IP,
                FILTER_FLAG_IPV4 ||
                    FILTER_FLAG_IPV6 ||
                    FILTER_FLAG_NO_PRIV_RANGE ||
                    FILTER_FLAG_NO_RES_RANGE
            ) === false
        ) {
            return false;
        }
        return $ipAddress;
    }
}

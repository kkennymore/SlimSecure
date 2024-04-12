<?php

namespace SlimSecure\Core;

use SlimSecure\Configs\Env;

/**
 * This abstract class provides security-related functionality for the Slimez music distribution system.
 * It includes methods for encryption, decryption, file encryption/decryption, IP address validation, and string manipulation.
 *
 * Author: Oaad Global
 * Developer: Hitek Financials Ltd
 * Year: 2024
 * Developer Contact: contact@tekfinancials.ng, kennethusiobaifo@yahoo.com
 * Project Name: Slimez
 * Description: Slimez.
 */
abstract class Security
{

    /**
     * Adds padding to a word.
     *
     * @param string $word The word to add padding to.
     * @param int $paddingLeft The number of left paddings.
     * @param int $paddingRight The number of right paddings.
     * @return string The word with padding added.
     */
    public static function addPadding(
        string $word,
        int $paddingLeft = 0,
        int $paddingRight = 0,
    ) {
        if ($paddingRight == 0 && $paddingLeft == 0) {
            return $word;
        }
        if ($paddingRight > 0 && $paddingLeft > 0) {
            return " " . $word . " ";
        }

        if ($paddingRight > 0) {
            return $word . " ";
        }
        if ($paddingLeft > 0) {
            return " " . $word;
        }
    }

    /**
     * Protects a string by adding slashes and removing tags.
     *
     * @param string $string The string to protect.
     * @return string The protected string.
     */
    public static function kenProtectFunc(string $string): string
    {
        return trim(strip_tags(addslashes($string)));
    }

    /**
     * Encrypts a string using AES-256-CBC encryption.
     *
     * @param string $string The string to encrypt.
     * @param string $key The encryption key.
     * @return string The encrypted string.
     */
    public static function kenEncrypt(string $string, string $key): string
    {
        $encryption_key = base64_decode($key);
        $iv = openssl_random_pseudo_bytes(
            openssl_cipher_iv_length('aes-256-cbc')
        );
        $encrypted = openssl_encrypt(
            $string,
            'aes-256-cbc',
            $encryption_key,
            0,
            $iv
        );
        return base64_encode($encrypted . '::' . $iv);
    }

    /**
     * Decrypts a string using AES-256-CBC decryption.
     *
     * @param string $string The string to decrypt.
     * @param string $key The decryption key.
     * @return mixed The decrypted string.
     */
    public static function kenDecrypt(string $string, string $key)
    {
        $encryption_key = base64_decode($key);
        @list($encrypted_data, $iv) = explode('::', base64_decode($string), 2);
        return @openssl_decrypt(
            $encrypted_data,
            'aes-256-cbc',
            $encryption_key,
            0,
            $iv
        );
    }

    /**
     * Generates a one-time password (OTP).
     *
     * @param int $length The length of the OTP.
     * @return string The generated OTP.
     */
    public static function OTPGen(int $length = 6)
    {
        $generator = time();
        $result = "";
        for ($i = 1; $i <= $length; $i++) {
            $result .= substr($generator, rand() % strlen($generator), 1);
        }
        return $result;
    }

    /**
     * Generates a UUID (Universally Unique Identifier).
     *
     * @return string The generated UUID.
     */
    public static function genUuid()
    {
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            // 32 bits for "time_low"
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),

            // 16 bits for "time_mid"
            mt_rand(0, 0xffff),

            // 16 bits for "time_hi_and_version",
            // four most significant bits holds version number 4
            mt_rand(0, 0x0fff) | 0x4000,

            // 16 bits, 8 bits for "clk_seq_hi_res",
            // 8 bits for "clk_seq_low",
            // two most significant bits holds zero and one for variant DCE1.1
            mt_rand(0, 0x3fff) | 0x8000,

            // 48 bits for "node"
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff)
        );
    }

    /**
     * Hashes a password using the default algorithm.
     *
     * @param string $password The password to hash.
     * @return string The hashed password.
     */
    public static function hashPassword($password)
    {
        $options = [
            'cost' => 12,
        ];

        return password_hash(trim($password), PASSWORD_DEFAULT);
    }

    /**
     * Verifies a password against a hashed password.
     *
     * @param string $password The password to verify.
     * @param string $hashedPassword The hashed password to compare against.
     * @return bool Returns true if the password is verified, false otherwise.
     */
    public static function verifyPassword($password, $hashedPassword)
    {
        return password_verify(trim($password), $hashedPassword);
    }

    /**
     * Checks if an email matches the regular expression pattern for email validation.
     *
     * @param string $email The email to validate.
     * @return string Returns true if the email is valid, false otherwise.
     */
    public static function emailRegularExpression(string $email): string
    {
        return preg_match(
            '/^(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){255,})(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){65,}@)(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22))(?:\.(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-[a-z0-9]+)*\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-[a-z0-9]+)*)|(?:\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\]))$/iD',
            $email
        );
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

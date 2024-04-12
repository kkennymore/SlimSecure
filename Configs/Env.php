<?php

namespace SlimSecure\Configs;

/**
 * Application configuration
 *
 * PHP version 5.4
 */
class Env
{

    /**
     * 
     */
    const API_TOKEN = "";
    /**
     * @var array support file format
     */
    const SUPPORTED_FILE_TYPES =  ['jpg', 'png', 'jpeg', 'gif', 'mp3', 'mp4', 'pdf', 'docx'];
    /**
     * @var string set the system name
     */
    const SYSTEM_NAME = 'KVPNSmart';
    /**
     * Database host
     * @var string
     */
    const DB_HOST = 'localhost';

    const LOCALHOST = "https://localhost";

    

     /**
     * Database name
     * @var string
     */
    const DB_NAME = 'Hitek';

    /**
     * Database user
     * @var string
     */

    const DB_USER = 'root';

    /**
     * Database password
     * @var string
     */
    const DB_PASSWORD = '';
    /**
     *@var string Database character set
     */
    const DB_CHARSET = 'utf8';
    /**
     * @var string Database driver
     */
    const DB_DRIVER = 'mysql';

    /**
     * Show or hide error messages on screen
     * @var boolean
     */
    const SHOW_ERRORS = true;

    const LOG_ERROR = true;

    /**
     *@var string page name
     */

    const PAGE_NAME = '';

    /**
     *@var string set the page search keywords
     */
    const PAGE_KEYWORDS = 'Music, radio, radio station';
    /**
     *@var string page theming color code
     */
    const PRIMARY_THEME = '#762262';
    /**
     *@var string page domain name
     */
    const DOMAIN_NAME = 'https://localhost';
    /**
     *@var string page loading entry point
     */
    const ENTRY_POINT = '/home/index';
    /**
     *@var array set language
     */
    const LANGUAGES = ['en', 'cn', 'fr', 'igbo', 'hausa', 'yoruba'];
    /**
     *@var string set default language
     */
    const DEFAULT_LANGUAGE = 'en';
    /**
     *@var string encryption key
     */
    const ENCRYPTION_KEY = '7434hgwhrtewvd';
    /**
     *@var string hashing key
     */
    const HASH_KEY = 'jkdtyualawo';
    /**
     *@var int cookie expiration time
     */
    const COOKIE_EXPIRATION_TIME_IN_HOURS = 1;
    /**
     *@var string admin emails
     */
    const DEFAULT_EMAIL = '';
    const EMAIL_HOST_USER = "";
    const EMAIL_HOST_PASSWORD = "";
    /**
     *@var int set the auto logout timer
     * if it is set to 0, it means the auto logout timer is disabled
     */
    const AUTO_LOGOUT_TIMEOUT = 0;
    /**
     * @var string log file directory
     */
    const AUTH_ERROR_DIRECTORY = 'Logs';
    /**
     * @var string error log name
     */
    const AUTH_ERROR_FILE_NAME = '';
    /**
     * lists of http response codes
     */
    const ALREADY_REPORTED = 208;
    const METHOD_NOT_ALLOWED = 405;
    const NOT_ACCEPTABLE = 406;
    const ERROR_CREATING_USER = 407;
    const USER_CREATED = 201;
    const WRONG_INPUT_METHOD = 422;
    const SERVER_ERROR_METHOD = 500;
    const FORBIDDEN_METHOD = 403;
    const SUCCESS_METHOD = 200;
    const NOT_FOUND_METHOD = 404;


    // 
    const SMTP_SERVER = '';
    const SMTP_PORT = 587;//465
    const SMTP_USERNAME = '';
    const SMTP_PASSWORD = '';
    const COMPANY_NAME = '';
    const MSG_API_TOKEN = '';
    const EMAIL_SERVER_ENDPOINT = '';
}
<?php

namespace SlimSecure\Configs;

/**
 * Env Class
 *
 * Stores environment-specific configurations for the SlimSecure application.
 * This class centralizes configuration settings such as database parameters,
 * system characteristics, and operational constants to facilitate easy management
 * and modification of settings that may vary between development, testing, and
 * production environments.
 */
class Env
{
    /**
     * API Token for external service authentication.
     * @var string
     */
    const API_TOKEN = "";

    /**
     * List of supported file types for uploads or processing.
     * @var array
     */
    const SUPPORTED_FILE_TYPES = ['jpg', 'png', 'jpeg', 'gif', 'mp3', 'mp4', 'pdf', 'docx'];

    /**
     * Name of the system, used in various parts of the application.
     * @var string
     */
    const SYSTEM_NAME = 'KVPNSmart';

    /**
     * Hostname for the database connection.
     * @var string
     */
    const DB_HOST = 'localhost';

    /**
     * URL for local development environment.
     * @var string
     */
    const LOCALHOST = "https://localhost";

    /**
     * Database name to connect to.
     * @var string
     */
    const DB_NAME = 'Hitek';

    /**
     * Database username for the connection.
     * @var string
     */
    const DB_USER = 'root';

    /**
     * Database password for the connection.
     * @var string
     */
    const DB_PASSWORD = '';

    /**
     * Character set used for the database connection to ensure proper encoding.
     * @var string
     */
    const DB_CHARSET = 'utf8';

    /**
     * Database driver, specifies the type of database.
     * @var string
     */
    const DB_DRIVER = 'mysql';

    /**
     * Toggle to show or hide error messages directly on screen.
     * Useful for debugging in a development environment.
     * @var boolean
     */
    const SHOW_ERRORS = true;

    /**
     * Toggle to log errors to a file or system logger.
     * @var boolean
     */
    const LOG_ERROR = true;

    /**
     * Default page name, used primarily in templating.
     * @var string
     */
    const PAGE_NAME = '';

    /**
     * Search keywords associated with the page, for SEO purposes.
     * @var string
     */
    const PAGE_KEYWORDS = 'Music, radio, radio station';

    /**
     * Primary theming color for the website, expressed as a HEX code.
     * @var string
     */
    const PRIMARY_THEME = '#762262';

    /**
     * Domain name where the application is hosted.
     * @var string
     */
    const DOMAIN_NAME = 'https://localhost';

    /**
     * Entry point for the application, typically a URL route.
     * @var string
     */
    const ENTRY_POINT = '/home/index';

    /**
     * Supported languages for the application interface.
     * @var array
     */
    const LANGUAGES = ['en', 'cn', 'fr', 'igbo', 'hausa', 'yoruba'];

    /**
     * Default language for the application.
     * @var string
     */
    const DEFAULT_LANGUAGE = 'en';

    /**
     * Key used for encryption operations within the application.
     * @var string
     */
    const ENCRYPTION_KEY = '7434hgwhrtewvd';

    /**
     * Key used for hashing operations, especially in securing data like passwords.
     * @var string
     */
    const HASH_KEY = 'jkdtyualawo';

    /**
     * Duration in hours after which a cookie should expire.
     * @var int
     */
    const COOKIE_EXPIRATION_TIME_IN_HOURS = 1;

    /**
     * Default email address used for administrative notifications.
     * @var string
     */
    const DEFAULT_EMAIL = '';

    /**
     * SMTP server details for sending email.
     * @var string
     */
    const SMTP_SERVER = '';
    const SMTP_PORT = 587; // Alternative port 465
    const SMTP_USERNAME = '';
    const SMTP_PASSWORD = '';

    /**
     * Name of the company, used in various branding and communication materials.
     * @var string
     */
    const COMPANY_NAME = '';

    /**
     * API token for sending messages through a third-party service.
     * @var string
     */
    const MSG_API_TOKEN = '';

    /**
     * Endpoint URL for the email server.
     * @var string
     */
    const EMAIL_SERVER_ENDPOINT = '';

    /**
     * Lists of HTTP response codes used throughout the application to standardize responses.
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

    /**
     * Auto logout timeout in seconds.
     * If set to 0, the auto logout feature is disabled.
     * @var int
     */
    const AUTO_LOGOUT_TIMEOUT = 0;

    /**
     * Directory where error logs are stored.
     * @var string
     */
    const AUTH_ERROR_DIRECTORY = 'Logs';

    /**
     * Specific name of the error log file.
     * @var string
     */
    const AUTH_ERROR_FILE_NAME = '';
}

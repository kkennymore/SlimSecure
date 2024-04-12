<?php

namespace SlimSecure\Core;

/**
 * SlimSecure SlimEngine Class
 *
 * Handles command-line operations to control the built-in PHP server.
 * Provides functionality to parse command-line arguments and execute server operations
 * such as starting the server on a specified IP, port, and document root.
 *
 * Author: Engineer Usiobaifo Kenneth
 * Developer: Hitek Financials Ltd
 * Year: 2024
 * Developer Contact: contact@tekfinancials.ng, kennethusiobaifo@yahoo.com
 * Project Name: SlimSecure
 * Description: SlimSecure.
 */
class SlimEngine
{
    /**
     * Constructs a new instance of SlimEngine.
     * 
     * Parses command-line arguments and executes commands like starting the server
     * or displaying help information.
     *
     * @param int $argc Number of command-line arguments.
     * @param array $argv Array of command-line arguments.
     */
    public function __construct($argc, $argv)
    {
        // Commands available for the server
        $commands = [
            'serve' => 'Start the PHP built-in web server',
            'help' => 'Display available commands and their descriptions',
        ];

        // Options available for the server command
        $options = [
            '-i' => [
                'description' => 'The IP address to bind the server to',
                'value_required' => true,
            ],
            '-p' => [
                'description' => 'The port to listen on',
                'value_required' => true,
            ],
            '-d' => [
                'description' => 'The document root directory',
                'value_required' => true,
            ],
        ];

        // Check for valid commands or help request
        if ($argc < 2 || $argv[1] !== 'serve') {
            echo "Invalid command.\n";
            exit(1);
        } elseif ($argc === 3 && ($argv[2] === '-h' || $argv[2] === '-help')) {
            $this->displayHelp($commands, $options);
            exit;
        }

        // Default server settings
        $defaultIp = '127.0.0.1';
        $defaultPort = 1987;
        $defaultDocRoot = getcwd();

        // Extract and set command-line options
        list($ip, $port, $docRoot) = $this->parseOptions($argc, $argv, $options, $defaultIp, $defaultPort, $defaultDocRoot);

        // Start the server with the specified settings
        $this->startServer($ip, $port, $docRoot);
    }

    /**
     * Displays help information for server commands and options.
     *
     * @param array $commands Array of command descriptions.
     * @param array $options Array of option descriptions.
     */
    private function displayHelp($commands, $options)
    {
        echo "Available commands:\n";
        foreach ($commands as $command => $description) {
            echo " - {$command}: {$description}\n";
        }
        echo "\nCommand options:\n";
        foreach ($options as $option => $info) {
            echo " {$option}: {$info['description']}\n";
        }
    }

    /**
     * Parses the command-line options for the server command.
     *
     * @param int $argc Number of command-line arguments.
     * @param array $argv Array of command-line arguments.
     * @param array $options Definitions of command-line options.
     * @param string $defaultIp Default IP address.
     * @param int $defaultPort Default port number.
     * @param string $defaultDocRoot Default document root directory.
     * @return array Array containing the IP, port, and document root directory settings.
     */
    private function parseOptions($argc, $argv, $options, $defaultIp, $defaultPort, $defaultDocRoot)
    {
        $ip = $defaultIp;
        $port = $defaultPort;
        $docRoot = $defaultDocRoot;

        for ($i = 2; $i < $argc; $i++) {
            $arg = $argv[$i];
            if (isset($options[$arg]) && $options[$arg]['value_required'] && isset($argv[$i + 1])) {
                $value = $argv[$i + 1];
                switch ($arg) {
                    case '-i':
                        $ip = $value;
                        break;
                    case '-p':
                        $port = $value;
                        break;
                    case '-d':
                        $docRoot = $value === '.' ? $defaultDocRoot : $value;
                        if (!is_dir($docRoot)) {
                            echo "Invalid document root directory: {$docRoot}\n";
                            exit(1);
                        }
                        break;
                }
                $i++;
            }
        }
        return [$ip, $port, $docRoot];
    }

    /**
     * Starts the PHP built-in web server with the specified settings.
     *
     * @param string $ip IP address the server binds to.
     * @param int $port Port the server listens on.
     * @param string $docRoot Document root directory.
     */
    private function startServer($ip, $port, $docRoot)
    {
        echo "Server started on IP: {$ip}, Port: {$port}, Document root: {$docRoot}\n";
        $serverCommand = "php -S {$ip}:{$port} -d display_errors=1 -d error_reporting=E_ALL -t " . escapeshellarg($docRoot);
        passthru($serverCommand);
    }
}

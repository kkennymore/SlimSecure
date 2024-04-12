<?php

namespace Core\Engine;

// serve.php
class SlimEngine
{
    public function __construct($argc, $argv)
    {

        if ($argc < 2 || $argv[1] !== 'serve') {
            echo "Invalid command.\n";
            exit(1);
        }

        $commands = [
            'serve' => 'Start the PHP built-in web server',
            'help' => 'Display available commands and their descriptions',
        ];

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

        if ($argc === 3 && ($argv[2] === '-h' || $argv[2] === '-help')) {
            echo "Available commands:\n";
            foreach ($commands as $command => $description) {
                echo " - {$command}: {$description}\n";
            }
            echo "\nCommand options:\n";
            foreach ($options as $option => $info) {
                echo " {$option}: {$info['description']}\n";
            }
            exit;
        }

        $defaultIp = '127.0.0.1';
        $defaultPort = 1987;
        $defaultDocRoot = getcwd();

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
                        if ($value === '.') {
                            $docRoot = $defaultDocRoot;
                        } else {
                            $docRoot = $value;
                            if (!is_dir($docRoot)) {
                                echo "Invalid document root directory: {$docRoot}\n";
                                exit(1);
                            }
                        }
                        break;
                }
                $i++;
            }
        }

        echo "Server started. Press Ctrl+C to exit.\n";
        echo "IP: {$ip}\n";
        echo "Port: {$port}\n";
        echo "Document root: {$docRoot}\n";

        // Define the server command
        $serverCommand = "php -S {$ip}:{$port}";

        // Enable Apache rewrite rule for .htaccess files
        $serverCommand .= " -d display_errors=1 -d error_reporting=E_ALL";

        $docRoot = realpath($docRoot);
        chdir($docRoot);
        $serverCommand .= " -t " . escapeshellarg($docRoot);

        passthru($serverCommand);
    }
}

<?php

namespace SlimSecure\Core;

/**
 * SlimKernel Class
 * 
 * Facilitates the automated generation of various structural components for the SlimSecure application,
 * including controllers, models, migrations, middlewares, and core classes. This class helps in
 * maintaining consistency and expediting development processes by automating boilerplate code creation.
 */
class SlimKernel
{
    /**
     * @var string The root directory path of the application.
     */
    protected $rootPath;

    /**
     * @var string The namespace under which the generated classes will be stored.
     */
    protected $namespace;

    /**
     * Constructs a new SlimKernel instance.
     * 
     * @param string $namespace The namespace for generated files.
     */
    public function __construct($namespace)
    {
        $this->rootPath = dirname(__DIR__);
        $this->namespace = $namespace;
    }

    /**
     * Generates a controller class file with a namespace and extends BaseController.
     *
     * @param string $filename The name of the controller class to generate.
     */
    public function generateController($filename)
    {
        $content = "<?php\n\n";
        $content .= "namespace {$this->namespace}\\Controllers;\n\n";
        $content .= "use SlimSecure\\Core\\BaseController;\n\n";
        $content .= "/**
* {$filename} Class basic
*
* This controller extends
* the BaseController where you can provides methods specifically designed to manage {$filename} logics
* within the SlimSecure application.
*/\n";
        $content .= "class {$filename} extends BaseController\n";
        $content .= "{\n";
        $content .= "}\n";

        $this->saveFile("App/Controllers/{$filename}.php", $content);
    }

    /**
     * Generates a model class file with a namespace and extends BaseModel.
     *
     * @param string $filename The name of the model class to generate.
     */
    public function generateModel($filename)
    {
        $content = "<?php\n\n";
        $content .= "namespace {$this->namespace}\\Models;\n\n";
        $content .= "use SlimSecure\\Core\\BaseModel;\n\n";
        $content .= "/**
* {$filename} Class basic
*This model extends BaseModel 
* the BaseController where you can provides methods specifically designed to manage {$filename} logics
* within the SlimSecure application.
*/\n";
        $content .= "class {$filename} extends BaseModel\n";
        $content .= "{\n";
        $content .= "}\n";

        $this->saveFile("App/Models/{$filename}.php", $content);
    }

    /**
     * Generates a migration class file with a namespace and extends BaseMigration.
     *
     * @param string $filename The name of the migration class to generate.
     */
    public function generateMigration($filename)
    {
        $content = "<?php\n\n";
        $content .= "namespace {$this->namespace}\\Migrations;\n\n";
        $content .= "use SlimSecure\\Core\\BaseMigration;\n\n";
        $content .= "/**
* {$filename} Class basic
* This model extends BaseMigration 
* the BaseMigration where you can provides methods specifically designed to manage {$filename} logics
* within the SlimSecure application.
*/\n";
        $content .= "class {$filename} extends BaseMigration\n";
        $content .= "{\n";
        $content .= "}\n";

        $this->saveFile("App/Migrations/{$filename}.php", $content);
    }

    /**
     * Generates a middleware class file within a specified namespace.
     *
     * @param string $filename The name of the middleware class to generate.
     */
    public function generateMiddleware($filename)
    {
        $content = "<?php\n\n";
        $content .= "namespace {$this->namespace}\\Middlewares;\n\n";
        $content .= "/**
* {$filename} Class basic
*
* you can define your middleware logics here that will intercept the application before thing gets executed by the controller or model
* here you can define user authentication and validation of requests
* within the SlimSecure application.
*/\n";
        $content .= "class {$filename}\n";
        $content .= "{\n";
        $content .= "}\n";

        $this->saveFile("App/Middlewares/{$filename}.php", $content);
    }

    /**
     * Generates a core class file within a specified namespace.
     *
     * @param string $filename The name of the core class to generate.
     */
    public function generateCoreClass($filename)
    {
        $content = "<?php\n\n";
        $content .= "namespace {$this->namespace}\\Core;\n\n";
        $content .= "/**
* SlimSecure Project - {$filename} Class
* 
* Provides a fundamental structure for building controller classes that handle
* different aspects of the SlimSecure application. This class is part of the core
* library of SlimSecure, developed by Hitek Financials Ltd.
*
* @author Engineer Usiobaifo Kenneth
* @contact contact@tekfinancials.ng, kennethusiobaifo@yahoo.com
* @year 2024
* @package SlimSecure\Core
*/
\n";
        $content .= "class {$filename}\n";
        $content .= "{\n";
        $content .= "   function _construct(){\n";
        $content .= "       //constructor logic\n";
        $content .= "   }\n";
        $content .= "}\n";

        $this->saveFile("Core/{$filename}.php", $content);
    }

    /**
     * Saves the generated content to a file in the specified path.
     *
     * Ensures that the directory for the file exists, creates it if it doesn't, and writes the
     * content to the file, echoing out the creation path upon success.
     *
     * @param string $path The path relative to the root where the file should be saved.
     * @param string $content The content to write to the file.
     */
    protected function saveFile($path, $content)
    {
        $fullPath = $this->rootPath . '/' . $path;
        if (!file_exists(dirname($fullPath))) {
            mkdir(dirname($fullPath), 0777, true);
        }
        
        file_put_contents($fullPath, $content);
        echo "File created: {$fullPath}\n";
    }
}

// Command Line Usage
// Provides an interface for command line interaction to create various types of class files.
//Examples are ./slim create:controller UserController, ./slim create:model UserModel, ./slim create:middleware Authentication
if (isset($argv[1]) && isset($argv[2])) {
    $command = $argv[1];
    $filename = $argv[2];

    $generator = new \SlimSecure\Core\SlimKernel('SlimSecure\App');
    $generatorCore = new \SlimSecure\Core\SlimKernel('SlimSecure');

    switch ($command) {
        case 'create:controller':
            $generator->generateController($filename);
            break;
        case 'create:model':
            $generator->generateModel($filename);
            break;
        case 'create:migration':
            $generator->generateMigration($filename);
            break;
        case 'create:middleware':
            $generator->generateMiddleware($filename);
            break;
        case 'create:core':
            $generatorCore->generateCoreClass($filename);
            break;
        default:
            echo "Invalid command.\n";
            break;
    }
} else {
    echo "Usage: ./slim [command] [filename]\n";
}

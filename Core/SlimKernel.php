<?php
namespace Core;
class SlimKernel
{
    protected $rootPath;
    protected $namespace;

    public function __construct($namespace)
    {
        $this->rootPath = dirname(dirname(__DIR__));
        $this->namespace = $namespace;
    }

    public function generateController($filename)
    {
        $content = "<?php\n\n";
        $content .= "namespace {$this->namespace}\\Controllers;\n\n";
        $content .= "use Core\\BaseController;\n\n";
        $content .= "class {$filename} extends BaseController\n";
        $content .= "{\n";
        $content .= "}\n";

        $this->saveFile("App/Controllers/{$filename}.php", $content);
    }

    public function generateModel($filename)
    {
        $content = "<?php\n\n";
        $content .= "namespace {$this->namespace}\\Models;\n\n";
        $content .= "use Core\\BaseModel;\n\n";
        $content .= "class {$filename} extends BaseModel\n";
        $content .= "{\n";
        $content .= "}\n";

        $this->saveFile("App/Models/{$filename}.php", $content);
    }

    public function generateMigration($filename)
    {
        $content = "<?php\n\n";
        $content .= "namespace {$this->namespace}\\Migrations;\n\n";
        $content .= "use Core\\Migrations\\BaseMigration;\n\n";
        $content .= "class {$filename} extends BaseMigration\n";
        $content .= "{\n";
        $content .= "}\n";

        $this->saveFile("App/Migrations/{$filename}.php", $content);
    }

    public function generateMiddleware($filename)
    {
        $content = "<?php\n\n";
        $content .= "namespace {$this->namespace}\\Middlewares;\n\n";
        $content .= "class {$filename}\n";
        $content .= "{\n";
        $content .= "}\n";

        $this->saveFile("App/Middlewares/{$filename}.php", $content);
    }

    public function generateCoreClass($filename)
    {
        $content = "<?php\n\n";
        $content .= "namespace {$this->namespace}\\Core;\n\n";
        $content .= "class {$filename}\n";
        $content .= "{\n";
        $content .= "}\n";

        $this->saveFile("Core/{$filename}.php", $content);
    }

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
if (isset($argv[1]) && isset($argv[2])) {
    $command = $argv[1];
    $filename = $argv[2];

    $generator = new \Core\SlimKernel('App');

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
            $generator->generateCoreClass($filename);
            break;
        default:
            echo "Invalid command.\n";
            break;
    }
} else {
    echo "Usage: php flare [command] [filename]\n";
}

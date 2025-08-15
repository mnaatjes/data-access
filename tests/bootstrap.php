<?php
    /**
     * Create Autoloader
     */
    
    spl_autoload_register(function ($className) {
        $namespaces = [
            'mnaatjes\\App\\' => __DIR__ . '/../src/',
            'mnaatjes\\Tests\\' => __DIR__ . '/../tests/'
        ];

        foreach ($namespaces as $prefix => $baseDir) {
            $len = strlen($prefix);
            if (strncmp($prefix, $className, $len) === 0) {
                $relativeClass = substr($className, $len);
                $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

                if (file_exists($file)) {
                    require $file;
                    return;
                }
            }
        }
    });

    /**
     * Declare namespaces to use
     */
    use mnaatjes\App\Utils\DotEnv;

    /**
     * Create .env reader instance
     */
    DotEnv::load(__DIR__ . "/.env");

    
?>
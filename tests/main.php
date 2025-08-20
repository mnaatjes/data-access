<?php
    /**
     * Testing Environment access point
     * @file tests/main.php
     */

    // TODO: Refactor Bootstrap

    /**
     * Defines the location of the entry-point of the application
     * @var string PROJECT_ROOT
     * @example public/index.php
     * @example tests/main.php
     */
    define("PROJECT_ROOT", dirname(__DIR__));
    /**
     * Require autoloader
     * Require bootstrap.php
     */
    require_once(PROJECT_ROOT . "/vendor/autoload.php");
    $container = require_once(PROJECT_ROOT . "/bootstrap.php");

    //var_dump($_ENV["DB_CONNECTION"]);

    //require_once(ROOT_DIR . "/bootstrap.php");
    return;
    /**
     * Connect to DB
     */
    /*
    use mnaatjes\App\DataAccess\Database;
    use mnaatjes\App\DataAccess\ORM;
    use mnaatjes\App\HttpCore\HttpRequest;
    use mnaatjes\App\HttpCore\HttpResponse;
    use mnaatjes\App\Utils\TestController;
    use mnaatjes\App\Utils\TestRepository;

    $db         = Database::getInstance();
    $orm        = new ORM($db);
    $repo       = new TestRepository($orm);
    $controller = new TestController($repo); 

    $req = new HttpRequest();
    $res = new HttpResponse();

    $controller->index($req, $res);
    */

?>
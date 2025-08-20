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
    var_dump($_SERVER["DOCUMENT_ROOT"]);
    var_dump(__DIR__);
    return;
    /**
     * Require autoloader
     * Require bootstrap.php
     */
    require_once(PROJECT_ROOT . "/vendor/autoload.php");
    $container = require_once(PROJECT_ROOT . "/bootstrap.php");

    /**
     * Connect to DB
     */
    use mnaatjes\mvcFramework\DataAccess\Database;
    use mnaatjes\mvcFramework\DataAccess\ORM;
    use mnaatjes\mvcFramework\HttpCore\HttpRequest;
    use mnaatjes\mvcFramework\HttpCore\HttpResponse;

    $db         = Database::getInstance();
    $orm        = new ORM($db);

    $req = new HttpRequest();
    $res = new HttpResponse();

?>
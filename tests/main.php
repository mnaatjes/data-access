<?php
    /**
     * Require Autoloader and Bootstrap
     */

    require_once(FRAMEWORK_PATH . "/vendor/autoload.php");
    require_once(FRAMEWORK_PATH . "/bootstrap.php");

    /**
     * Load ENV Variables
     */
    use mnaatjes\mvcFramework\Utils\DotEnv;
    DotEnv::load(FRAMEWORK_PATH . "/tests/.env");

    use mnaatjes\mvcFramework\DataAccess\Database;
    use mnaatjes\mvcFramework\DataAccess\ORM;
    use mnaatjes\mvcFramework\HttpCore\HttpRequest;
    use mnaatjes\mvcFramework\HttpCore\HttpResponse;

    $db         = Database::getInstance();
    $orm        = new ORM($db);

    $req = new HttpRequest();
    $res = new HttpResponse();

    

?>
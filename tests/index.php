<?php

    require_once("bootstrap.php");
    /**
     * Connect to DB
     */

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


?>
<?php
    /**
     * Require Autoloader and Bootstrap
     */

    require_once(FRAMEWORK_PATH . "/vendor/autoload.php");
    $container = require_once(FRAMEWORK_PATH . "/bootstrap.php");

    /**
     * Load ENV Variables
     */
    use mnaatjes\mvcFramework\Utils\DotEnv;
    DotEnv::load(FRAMEWORK_PATH . "/tests/.env");

    use mnaatjes\mvcFramework\DataAccess\Database;
    use mnaatjes\mvcFramework\DataAccess\ORM;
    use mnaatjes\mvcFramework\HttpCore\HttpRequest;
    use mnaatjes\mvcFramework\HttpCore\HttpResponse;
    use mnaatjes\mvcFramework\SessionsCore\SessionManager;
    use mnaatjes\mvcFramework\HttpCore\Router;
    use PharIo\Manifest\Author;

    $db         = Database::getInstance();
    $orm        = new ORM($db);

    $req = new HttpRequest();
    $res = new HttpResponse();

    $container->setShared(SessionManager::class, new SessionManager());

    $router = new Router($container);
    /*
    $router->get("/", function($_, $res) use($container){
        $session = $container->get(SessionManager::class);
        //$session->start();
        $session->set("user_id", "gemini");
        $res->redirect("/index.php/data");
    });

    $router->get("/data", function($_, $res) use($container){
        $session = $container->get(SessionManager::class);
        $session->set("data", [
            'orderId' => 'ORD-987654321',
            'customer' => [
                'id' => 205,
                'name' => 'Jane Doe',
                'email' => 'jane.doe@email.com',
                'address' => '456 Model Way, Hobbytown, USA 12345'
            ],
            'items' => [
                [
                    'productId' => 'AIRCRAFT-012',
                    'productName' => 'F-16 Fighting Falcon 1/48 Scale Model Kit',
                    'category' => 'Scale Model Aircraft',
                    'manufacturer' => 'Tamiya',
                    'price' => 59.99,
                    'quantity' => 1,
                    'sku' => 'TM-61012'
                ],
                [
                    'productId' => 'TOOLS-007',
                    'productName' => 'Hobby Knife Set with Blades',
                    'category' => 'Scale Model Tools',
                    'manufacturer' => 'Excel Blades',
                    'price' => 14.50,
                    'quantity' => 2,
                    'sku' => 'XB-16001'
                ],
                [
                    'productId' => 'AIRCRAFT-045',
                    'productName' => 'Boeing 747 Jumbo Jet 1/144 Scale Kit',
                    'category' => 'Scale Model Aircraft',
                    'manufacturer' => 'Revell',
                    'price' => 38.75,
                    'quantity' => 1,
                    'sku' => 'RV-04212'
                ],
                [
                    'productId' => 'PAINT-021',
                    'productName' => 'Acrylic Paint Set - Military Colors',
                    'category' => 'Paints & Supplies',
                    'manufacturer' => 'Vallejo',
                    'price' => 25.00,
                    'quantity' => 1,
                    'sku' => 'VJ-70119'
                ]
            ],
            'totals' => [
                'subtotal' => 153.24,
                'shipping' => 9.95,
                'tax' => 7.66,
                'grandTotal' => 170.85
            ],
            'status' => 'Pending',
            'dateCreated' => '2025-08-20 16:30:00'
        ]);
        $res->redirect("/index.php/show");
    });

    $router->get("/show", function($_, $res) use($container){
        $session = $container->get(SessionManager::class);
        $data = $session->getAll();

        echo "<pre>" . json_encode($data, JSON_PRETTY_PRINT) . "</pre>";
    });

    */
    /**
     * Debugging Middleware
     */
    $authMiddle = function(HttpRequest $req, HttpResponse $res, callable $next) use($container){
        $session = $container->get(SessionManager::class);
        $session->set("username", "gemini");
        var_dump("Middleware");
        $next();
    };

    $router->get("/middle", function(HttpRequest $req, HttpResponse $res, array $params) use($container){
        $session = $container->get(SessionManager::class);
        var_dump("Handler");
        var_dump($session->get("username"));
        
    }, [$authMiddle, function($req, $res, $next){
        var_dump("Second Middleware");
        $next();
    }]);
    
    /**
     * Middleware testing
     */

    $router->dispatch($req, $res);



?>
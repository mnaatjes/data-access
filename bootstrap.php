<?php

    /**
     * @file bootstrap.php
     * @since 1.0.0:
     * - ROOT_DIR removed and replaced with PROJECT_ROOT
     * 
     * 
     * @version 1.1.0
     */

    /**
     * Load Configuration from PROJECT_ROOT directory as defined in entry-point
     */
    use mnaatjes\mvcFramework\Utils\Config;
    $config = new Config(PROJECT_ROOT . "/tests/.env");

    /**
     * Instantiate DI Container and Return
     * @var Container $container
     */
    use mnaatjes\mvcFramework\Container;
    $container = new Container();
    return $container;
    
?>
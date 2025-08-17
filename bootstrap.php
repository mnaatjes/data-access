<?php

    /**
     * Declare ROOT_DIR path
     * @var string ROOT_DIR
     * - Development: dirname(__DIR__, 1) . "/mvc-skeleton"
     * - Package: dirname(__DIR__, 3)
     */
    define("ROOT_DIR", dirname(__DIR__, 1) . "/mvc-skeleton");
    
    /**
     * Set error_handling
     */
    
    /**
     * Instantiate DI Container and Return
     * @var Container $container
     */
    use mnaatjes\mvcFramework\Container;
    $container = new Container();
    return $container;

    
?>
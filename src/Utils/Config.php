<?php

    namespace mnaatjes\mvcFramework\Utils;

use Exception;
use mnaatjes\mvcFramework\Utils\DotEnv;
    /**-------------------------------------------------------------------------*/
    /**
     * Configuration Class for MVC-Framework
     */
    /**-------------------------------------------------------------------------*/
    class Config {

        private array $props;

        /**-------------------------------------------------------------------------*/
        /**
         * Construct
         * 
         * @param string $env_filepath
         */
        /**-------------------------------------------------------------------------*/
        public function __construct(string $env_filepath){
            // Load ENV Variables
            $loaded = $this->loadENV($env_filepath);

            if($loaded === true){
                // Configure Environment
                $this->setErrors();

                // Define Path Constants
                $this->definePathConstants();
            }
            

        }

        /**-------------------------------------------------------------------------*/
        /**
         * Load Env Variables
         */
        /**-------------------------------------------------------------------------*/
        private function loadENV(string $path){
            try {
                DotEnv::load($path);
                return true;
            } catch (\Exception $e){
                return false;
            }
            
        }

        /**-------------------------------------------------------------------------*/
        /**
         * Retrieves an environment variable using the DotEnv::get() method.
         *
         * This method acts as a convenient wrapper to abstract the
         * direct call to the DotEnv class
         *
         * @param string $key The environment variable key.
         * @param mixed $default Optional default value to return if the key is not found.
         * @return mixed The value of the environment variable, or the default value.
         */
        /**-------------------------------------------------------------------------*/
        public function get(string $key, $default = null) {
            return DotEnv::get($key, $default);
        }

        /**-------------------------------------------------------------------------*/
        /**
         * Retrieves an environment variable using the DotEnv::get() method.
         *
         * @param string $key The environment variable key.
         * @param mixed $value Value for environment variable
         */
        /**-------------------------------------------------------------------------*/
        public function set(string $key, $value) {
            // Use DotEnv static method
            return DotEnv::set(strtoupper($key), $value);
        }

        /**-------------------------------------------------------------------------*/
        /**
         * Check if env key exists
         */
        /**-------------------------------------------------------------------------*/
        public function has(string $key){
            return DotEnv::has($key);
        }
        /**-------------------------------------------------------------------------*/
        /**
         * Configure Environment
         */
        /**-------------------------------------------------------------------------*/

        /**-------------------------------------------------------------------------*/
        /**
         * Set Errors
         */
        /**-------------------------------------------------------------------------*/
        private function setErrors(){

            /**
             * Determine Application Environment
             * - Default to dev with errors on
             */
            switch($this->get("APP_ENV", "dev")){
                /**
                 * Case: Production
                 */
                case 'production':
                case 'prod':
                    // Check debug also set
                    if($this->get("APP_DEBUG", false) === false){
                        // Turn Off Errors
                        self::disableErrors();
                    } else {
                        // Turn on Errors
                        self::enableErrors();
                    }
                    break;
                /**
                 * Case: Local
                 */
                case 'local':
                    break;
                /**
                 * Case: 'Staging'
                 * Default: Development
                 */
                case 'staging':
                case 'development':
                case 'dev':
                    // Check debug also set
                    if($this->get("APP_DEBUG", true) === true){
                        // Turn on Errors
                        self::enableErrors();
                    } else {
                        // Turn Off Errors
                        self::disableErrors();
                    }
                default: 

            }
        }

        /**-------------------------------------------------------------------------*/
        /**
         * Enable Errors
         * @return void
         */
        /**-------------------------------------------------------------------------*/
        public static function enableErrors(): void{
            // In development/staging, display all errors for debugging.
            ini_set('display_errors', '1');
            ini_set('display_startup_errors', '1');
            error_reporting(E_ALL);
        }
        /**-------------------------------------------------------------------------*/
        /**
         * Disable Errors
         * @return void
         */
        /**-------------------------------------------------------------------------*/
        public static function disableErrors(): void{
            ini_set('display_errors', '0');
            ini_set('display_startup_errors', '0');
            error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
        }

        /**-------------------------------------------------------------------------*/
        /**
         * Define Path Constants
         */
        /**-------------------------------------------------------------------------*/
        private function definePathConstants(){
            // Validate Project Root
            if(!defined("PROJECT_ROOT")){
                throw new Exception("Undefined global variable PROJECT_ROOT! You MUST define this in the app-entry-point using define('PROJECT_ROOT', dirname(__DIR__))");
            }

            // Check Framework Environment
            if($this->get("FRAMEWORK_ENV", "production") !== "production"){
                
            }
        }

    }
?>
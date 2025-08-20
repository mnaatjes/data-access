<?php
    /**
     * Declare Namespace
     */
    namespace mnaatjes\mvcFramework\Utils;
    
    /**-------------------------------------------------------------------------*/
    /**
     * DotEnv (.env) Class for loading Environment Variables
     * 
     * @since 1.0.0:
     * - Created
     * - Added to data-access/utils
     * 
     * @since 1.1.0:
     * - Added get() method
     * - Added set() method
     * - Converted methods to static
     * 
     * @version 1.1.1
     */
    /**-------------------------------------------------------------------------*/
    class DotEnv {

        /**
         * Filepath to .env file
         * @var string $path
         */

        protected string $path;

        /**-------------------------------------------------------------------------*/
        /**
         * Load .env file
         * 
         * @static
         * @param string $path
         * @return 
         * @throws \RuntimeException if .env file not readable
         */
        /**-------------------------------------------------------------------------*/
        public static function load(string $path){
            // Validate path
            if(!is_readable($path)){
                throw new \RuntimeException("Unable to read file: " . $path);
            }

            /**
             * Open file and load lines into array
             * @var array $lines
             */
            $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            
            /**
             * Loop and assign as Environment Variables:
             * - Skip comments
             * - Grab $key => $value pairs
             * - Sanitize entires & strip quotes
             * - Set $_ENV Variables
             */
            foreach($lines as $line){
                // Skip comments
                if(strpos(trim($line), "#") === 0){
                    continue;
                }

                // Explode into array of key, value pairs to validate
                $parts = explode("=", $line, 2);

                // Check for equals operator
                if(count($parts) !== 2){
                    continue;
                }

                // Grab key, value pairs
                list($key, $value) = $parts;

                // Sanitize
                $key    = trim($key);
                $value  = trim($value);

                // Strip quotes from values if exist
                if(in_array($value[0] ?? '', ['"', "'"])){ // <- if NULL, set empty to avoid error
                    $value = substr($value, 1, -1);
                }

                // Set ENV Variables
                if(!array_key_exists($key, $_SERVER) && !array_key_exists($key, $_ENV)){
                    // Check if the value is a JSON string
                    $decoded = json_decode($value, true);
                    if(json_last_error() === JSON_ERROR_NONE){
                        // Store --> cannot use setENV
                        $_ENV[$key]     = $decoded;
                        $_SERVER[$key]  = $decoded;

                    } else {
                        // Put ENV variables (string values)
                        putenv(sprintf('%s=%s', $key, $value));
                        $_ENV[$key]     = $value;
                        $_SERVER[$key]  = $value;
                    }
                }
            }
        }

        /**-------------------------------------------------------------------------*/
        /**
         * Get an environment variable.
         * * This method safely retrieves an environment variable by checking
         * multiple sources: $_ENV, $_SERVER, and getenv().
         * * @static
         * @param string $key The environment variable key.
         * @param mixed $default Optional default value to return if the key is not found.
         * @return mixed The value of the environment variable, or the default value.
         */
        /**-------------------------------------------------------------------------*/
        public static function get(string $key, $default = null) {
            // Check $_ENV first, as it's typically the most reliable source for loaded variables
            if (isset($_ENV[$key])) {
                return $_ENV[$key];
            }

            // Check $_SERVER as a fallback
            if (isset($_SERVER[$key])) {
                return $_SERVER[$key];
            }

            // Check getenv() as a final fallback for system-level variables
            $value = getenv($key);

            // If getenv() returns false, the variable was not found, so return the default
            if ($value !== false) {
                return $value;
            }

            return $default;
        }

        /**-------------------------------------------------------------------------*/
        /**
         * Set an environment variable.
         * * This method adds or updates an environment variable across
         * $_ENV, $_SERVER, and the putenv() function. It handles both
         * string and non-string values.
         * * @static
         * @param string $key The environment variable key.
         * @param mixed $value The value to set.
         * @return void
         */
        /**-------------------------------------------------------------------------*/
        public static function set(string $key, $value): void {
            // Set the value in the $_ENV superglobal
            $_ENV[$key] = $value;

            // Set the value in the $_SERVER superglobal
            $_SERVER[$key] = $value;

            // For string values, also use putenv() for system-level access
            if (is_string($value)) {
                putenv(sprintf('%s=%s', $key, $value));
            }
        }

        /**-------------------------------------------------------------------------*/
        /**
         * Checks if an environment variable exists.
         *
         * This method provides a clean way to check for the existence of an
         * environment variable without retrieving its value.
         *
         * @param string $key The environment variable key.
         * @return bool True if the variable is defined, false otherwise.
         */
        /**-------------------------------------------------------------------------*/
        public static function has(string $key): bool {
            return DotEnv::get($key) !== null;
        }

    }
?>
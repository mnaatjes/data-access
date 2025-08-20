<?php

    namespace mnaatjes\mvcFramework\SessionsCore;

    /**-------------------------------------------------------------------------*/
    /**
     * An object-oriented wrapper for the PHP $_SESSION superglobal, designed
     * for use with dependency injection. This class provides a clean API for
     * managing session data without relying on static methods or global state.
     * 
     * @since 1.0.0: Created
     * @since 1.0.1:  
     * 
     * @version 1.1.0
     */
    /**-------------------------------------------------------------------------*/
    class SessionManager
    {
        /**
         * @var bool Tracks if the session has been started.
         */
        private bool $is_session_started = false;

        /**-------------------------------------------------------------------------*/
        /**
         * Starts the PHP session.
         * It checks if the session has already been started to prevent errors.
         * @return void
         */
        /**-------------------------------------------------------------------------*/
        public function start(): void{
            if ($this->is_session_started === false) {
                session_start();
                $this->is_session_started = true;
            }
        }

        /**-------------------------------------------------------------------------*/
        /**
         * Sets a session variable.
         * @param string $key The key for the session variable.
         * @param mixed $value The value to store.
         * @return void
         */
        /**-------------------------------------------------------------------------*/
        public function set(string $key, mixed $value): void{
            $this->start();
            $_SESSION[$key] = $value;
        }

        /**-------------------------------------------------------------------------*/
        /**
         * Gets a session variable.
         * @param string $key The key of the session variable.
         * @param mixed $default The default value to return if the key doesn't exist.
         * @return mixed The value of the session variable or the default value.
         */
        /**-------------------------------------------------------------------------*/
        public function get(string $key, mixed $default = null): mixed{
            $this->start();
            return $_SESSION[$key] ?? $default;
        }

        /**-------------------------------------------------------------------------*/
        /**
         * Checks if a session variable exists.
         * @param string $key The key to check.
         * @return bool True if the key exists, false otherwise.
         */
        /**-------------------------------------------------------------------------*/
        public function has(string $key): bool{
            $this->start();
            return isset($_SESSION[$key]);
        }

        /**-------------------------------------------------------------------------*/
        /**
         * Removes a single session variable.
         * @param string $key The key of the variable to remove.
         * @return void
         */
        /**-------------------------------------------------------------------------*/
        public function remove(string $key): void{
            $this->start();
            unset($_SESSION[$key]);
        }

        /**-------------------------------------------------------------------------*/
        /**
         * Clears all session data.
         * @return void
         */
        /**-------------------------------------------------------------------------*/
        public function clear(): void{
            $this->start();
            session_unset();
        }

        /**-------------------------------------------------------------------------*/
        /**
         * Destroys the entire session and the session cookie.
         * This is typically used for user logout.
         * @return void
         */
        /**-------------------------------------------------------------------------*/
        public function destroy(): void{
            $this->start();
            session_destroy();
            $_SESSION = [];
        }

        /**-------------------------------------------------------------------------*/
        /**
         * Regenerates the session ID to prevent session fixation attacks.
         * This should be called after a successful login.
         * @return void
         */
        /**-------------------------------------------------------------------------*/
        public function regenerateId(): void{
            $this->start();
            session_regenerate_id(true);
        }

        /**-------------------------------------------------------------------------*/
        /**
         * Gets all session variables as an array.
         * @return array All key-value pairs in the session.
         */
        /**-------------------------------------------------------------------------*/
        public function getAll(): array
        {
            $this->start();
            return $_SESSION;
        }
    }


?>
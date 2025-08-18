<?php

    namespace mnaatjes\mvcFramework\HttpCore;

    /**-------------------------------------------------------------------------*/
    /**
     * @version 1.1.0
     * @since 1.0.0:
     * - Added render() methods
     */
    /**-------------------------------------------------------------------------*/
    class HttpResponse
    {
        private $headers = [];
        private $body = '';
        private $statusCode = 200;
        private $statusText = 'OK';
        private $viewsPath;

        /**-------------------------------------------------------------------------*/
        /**
         * Construct
         */
        /**-------------------------------------------------------------------------*/
        public function __construct(string $viewsPath = __DIR__ . '/../../views')
        {
            // Set a default views path. Adjust as needed for your project structure.
            $this->viewsPath = rtrim($viewsPath, '/');
        }

        /**-------------------------------------------------------------------------*/
        /**
         * Set the HTTP status code and optional status text.
         *
         * @param int $statusCode The HTTP status code (e.g., 200, 404, 500).
         * @param string $statusText Optional custom status text. If not provided,
         * a default will be used based on the status code.
         * @return self
         */
        /**-------------------------------------------------------------------------*/
        public function setStatusCode(int $statusCode, string $statusText = ''): self
        {
            $this->statusCode = $statusCode;
            $this->statusText = $statusText ?: $this->getDefaultStatusText($statusCode);
            return $this;
        }

        /**-------------------------------------------------------------------------*/
        /**
         * Add a custom HTTP header.
         *
         * @param string $name The name of the header (e.g., 'Content-Type').
         * @param string $value The value of the header (e.g., 'application/json').
         * @param bool $replace Whether to replace an existing header with the same name.
         * Defaults to true.
         * @return self
         */
        /**-------------------------------------------------------------------------*/
        public function addHeader(string $name, string $value, bool $replace = true): self
        {
            $this->headers[] = ['name' => $name, 'value' => $value, 'replace' => $replace];
            return $this;
        }

        /**-------------------------------------------------------------------------*/
        /**
         * Set the response body.
         *
         * @param string $body The content of the response body.
         * @return self
         */
        /**-------------------------------------------------------------------------*/
        public function setBody(string $body): self
        {
            $this->body = $body;
            return $this;
        }

        /**-------------------------------------------------------------------------*/
        /**
         * Send all headers and the response body.
         * This method will prevent further output once called.
         *
         * @return void
         */
        /**-------------------------------------------------------------------------*/
        public function send(): void
        {
            // Set the HTTP status line
            header(sprintf('HTTP/1.1 %d %s', $this->statusCode, $this->statusText), true, $this->statusCode);

            // Send all custom headers
            foreach ($this->headers as $header) {
                header(sprintf('%s: %s', $header['name'], $header['value']), $header['replace']);
            }

            // Send the response body
            echo $this->body;

            // Ensure no further output is sent
            exit();
        }

        /**-------------------------------------------------------------------------*/
        /**
         * Sends a JSON response with the provided data.
         *
         * This method automatically sets the Content-Type header to `application/json`,
         * encodes the data to a JSON string, and then sends the full response.
         *
         * @param mixed $data The data to be encoded and sent as JSON.
         * @param int $statusCode The HTTP status code for the response. Defaults to 200.
         * @return void
         */
        /**-------------------------------------------------------------------------*/
        public function sendJson(mixed $data, int $statusCode = 200): void
        {
            // Set the status code for the response
            $this->setStatusCode($statusCode);

            // Set the Content-Type header to indicate a JSON response
            $this->addHeader('Content-Type', 'application/json');
            
            // Attempt to encode the data into a JSON string
            try {
                $jsonBody = json_encode($data, JSON_THROW_ON_ERROR);
                $this->setBody($jsonBody);
            } catch (\JsonException $e) {
                // Handle JSON encoding errors, e.g., for non-serializable data.
                // In this case, we'll send a 500 Internal Server Error.
                $this->setStatusCode(500, 'Internal Server Error');
                $this->setBody(json_encode(['error' => 'Failed to encode JSON response.']));
            }

            // Send the complete response
            $this->send();
        }

        /**-------------------------------------------------------------------------*/
        /**
         * Helper method to get default HTTP status text for common status codes.
         *
         * @param int $statusCode The HTTP status code.
         * @return string The default status text.
         */
        /**-------------------------------------------------------------------------*/
        private function getDefaultStatusText(int $statusCode): string
        {
            switch ($statusCode) {
                case 100: return 'Continue';
                case 101: return 'Switching Protocols';
                case 200: return 'OK';
                case 201: return 'Created';
                case 202: return 'Accepted';
                case 203: return 'Non-Authoritative Information';
                case 204: return 'No Content';
                case 205: return 'Reset Content';
                case 206: return 'Partial Content';
                case 300: return 'Multiple Choices';
                case 301: return 'Moved Permanently';
                case 302: return 'Found';
                case 303: return 'See Other';
                case 304: return 'Not Modified';
                case 307: return 'Temporary Redirect';
                case 308: return 'Permanent Redirect';
                case 400: return 'Bad Request';
                case 401: return 'Unauthorized';
                case 403: return 'Forbidden';
                case 404: return 'Not Found';
                case 405: return 'Method Not Allowed';
                case 406: return 'Not Acceptable';
                case 408: return 'Request Timeout';
                case 409: return 'Conflict';
                case 410: return 'Gone';
                case 411: return 'Length Required';
                case 412: return 'Precondition Failed';
                case 413: return 'Payload Too Large';
                case 414: return 'URI Too Long';
                case 415: return 'Unsupported Media Type';
                case 429: return 'Too Many Requests';
                case 500: return 'Internal Server Error';
                case 501: return 'Not Implemented';
                case 502: return 'Bad Gateway';
                case 503: return 'Service Unavailable';
                case 504: return 'Gateway Timeout';
                case 505: return 'HTTP Version Not Supported';
                default: return 'Unknown Status';
            }
        }
        
        /**-------------------------------------------------------------------------*/
        /**
         * Sends a rendered HTML response using a view and a master layout.
         *
         * This method automatically sets the Content-Type header to `text/html`,
         * renders the specified view file, and then injects that content into a
         * layout file before sending the complete response.
         *
         * @param string $view The name of the view file to render (e.g., 'home' for home.php).
         * @param array $data An associative array of data to pass to the view.
         * @param int $statusCode The HTTP status code for the response. Defaults to 200.
         * @return void
         */
        /**-------------------------------------------------------------------------*/
        public function render(string $view, array $data = [], int $statusCode = 200): void
        {
            // Set the HTTP status code and Content-Type header
            $this->setStatusCode($statusCode);
            $this->addHeader('Content-Type', 'text/html; charset=UTF-8');
            
            // Render the specific view file and capture its output
            $viewContent = $this->renderView($view, $data);

            // Render the master layout file, passing the view content into it
            $finalBody = $this->renderLayout($viewContent);

            // Set the body and send the complete response
            $this->setBody($finalBody);
            $this->send();
        }

        /**-------------------------------------------------------------------------*/
        /**
         * Renders a specific view file from the views directory.
         *
         * This method uses output buffering to capture the HTML output of a view
         * file, allowing it to be returned as a string.
         *
         * @param string $view The name of the view file (e.g., 'home').
         * @param array $data An associative array of data to pass to the view.
         * @return string The rendered HTML content of the view.
         */
        /**-------------------------------------------------------------------------*/
        private function renderView(string $view, array $data): string
        {
            $viewPath = $this->viewsPath . DIRECTORY_SEPARATOR . $view . '.php';

            if (!file_exists($viewPath)) {
                // A more robust application would handle this with a 404 error page.
                throw new \Exception("View file not found: " . $viewPath);
            }

            // Make the data variables accessible to the view file
            extract($data);
            
            // Start output buffering to capture the view's content
            ob_start();
            include $viewPath;
            return ob_get_clean();
        }

        /**-------------------------------------------------------------------------*/
        /**
         * Renders the master layout file, injecting the view content.
         *
         * This method assumes a main layout file exists (e.g., `views/layouts/main.php`)
         * with a variable named `$content` to hold the view's HTML.
         *
         * @param string $content The rendered content of the view.
         * @return string The final, complete HTML of the page.
         */
        /**-------------------------------------------------------------------------*/
        private function renderLayout(string $content): string
        {
            $layoutPath = $this->viewsPath . DIRECTORY_SEPARATOR . 'layouts' . DIRECTORY_SEPARATOR . 'main.php';

            if (!file_exists($layoutPath)) {
                throw new \Exception("Layout file not found: " . $layoutPath);
            }

            ob_start();
            include $layoutPath;
            return ob_get_clean();
        }
    }
?>
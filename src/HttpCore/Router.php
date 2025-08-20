<?php
    
    namespace mnaatjes\mvcFramework\HttpCore;
    use mnaatjes\mvcFramework\HttpCore\HttpRequest;
    use mnaatjes\mvcFramework\HttpCore\HttpResponse;
    use mnaatjes\mvcFramework\Container;

    /**
     * PHP Simple HTTP Manager:
     * * Lightweight HTTP Request and Response manager with Router
     * * @author Michael Naatjes michael.naatjes87@gmail.com
     * @version 1.2.2
     * @since 1.0.0
     * - Created
     * - Integrated
     * - Tested
     * * @since 1.1.0:
     * - Added instance parameter $container
     * - Modified addRoute() method:
     * - Modified dispatch() method:
     * * @since 1.2.0:
     * - Modified class to allow inclusion of middleware
     * - Modified class to pass next() callable
     * - Added middleware array property
     * - Modified addRoute() method:
     * - added param array $middleware to addRoute() and get(), post(), put(), etc. methods
     * - updated internal routes array to store middleware
     * - Added addMiddleware() method
     * * @since 1.2.1:
     * - Fixed the `get`, `post`, `put`, and `delete` methods to pass the middleware parameter to `addRoute`.
     * - Fixed `dispatch()` to use the null coalescing operator for middleware, preventing "Undefined array key" warnings.
     * - Updated `processMiddleware()` to correctly handle callable handlers.
     * * @since 1.2.2:
     * - Fixed a critical bug in `dispatch()` that caused a TypeError when a route had no middleware defined.
     */

    /**
     * Require Framework Classes
     * TODO: Move to Container Dependency Injection
     */
    //require_once('HttpRequest.php');
    //require_once('HttpResponse.php');

    /**-------------------------------------------------------------------------*/
    /**
     * Router Class
     * */
    /**-------------------------------------------------------------------------*/
    class Router
    {
        private array $routes = [];
        private array $middleware = [];
        private Container $container;
        public HttpRequest $request;
        public HttpResponse $response;


        public function __construct(Container $container) {
            $this->container = $container;
        }

        /**-------------------------------------------------------------------------*/
        /**
         * Add global middleware to be executed on every request.
         *
         * @param callable|array|string $middleware The middleware handler.
         * @return self
         */
        /**-------------------------------------------------------------------------*/
        public function addMiddleware(callable|array|string $middleware): self
        {
            $this->middleware[] = $middleware;
            return $this;
        }

        /**-------------------------------------------------------------------------*/
        /**
         * Add a GET route.
         *
         * @param string $path The URL path (e.g., '/users', '/users/{id}').
         * @param string|array|callable $handler The callback function or method to execute.
         * @param array $middleware An array of middleware for this specific route.
         * * @example
         * $router->get("/", [TestController::class, "index"]);
         * $router->get("/", "TestController@index()");
         * * @return self
         */
        /**-------------------------------------------------------------------------*/
        public function get(string $path, string|array|callable $handler, array $middleware = []): self{
            $this->addRoute('GET', $path, $handler, $middleware);
            return $this;
        }

        /**-------------------------------------------------------------------------*/
        /**
         * Add a POST route.
         *
         * @param string $path The URL path.
         * @param string|array|callable $handler The callback function or method to execute.
         * @param array $middleware An array of middleware for this specific route.
         * @return self
         */
        /**-------------------------------------------------------------------------*/
        public function post(string $path, string|array|callable $handler, array $middleware = []): self{
            $this->addRoute('POST', $path, $handler, $middleware);
            return $this;
        }

        /**-------------------------------------------------------------------------*/
        /**
         * Add a PUT route.
         *
         * @param string $path The URL path.
         * @param string|array|callable $handler The callback function or method to execute.
         * @param array $middleware An array of middleware for this specific route.
         * @return self
         */
        /**-------------------------------------------------------------------------*/
        public function put(string $path, string|array|callable $handler, array $middleware = []): self{
            $this->addRoute('PUT', $path, $handler, $middleware);
            return $this;
        }

        /**-------------------------------------------------------------------------*/
        /**
         * Add a DELETE route.
         *
         * @param string $path The URL path.
         * @param string|array|callable $handler The callback function or method to execute.
         * @param array $middleware An array of middleware for this specific route.
         * @return self
         */
        /**-------------------------------------------------------------------------*/
        public function delete(string $path, string|array|callable $handler, array $middleware = []): self{
            $this->addRoute('DELETE', $path, $handler, $middleware);
            return $this;
        }

        /**-------------------------------------------------------------------------*/
        /**
         * Add a route to the internal routes array.
         *
         * @param string $method The HTTP method (e.g., 'GET', 'POST').
         * @param string $path The URL path.
         * @param string|array|callable $handler The callback function or method.
         * @param array $middleware An array of middleware for this specific route.
         * @return void
         */
        /**-------------------------------------------------------------------------*/
        private function addRoute(string $method, string $path, string|array|callable $handler, array $middleware = []): void{
            // Convert path to a regex pattern, handling dynamic segments like {id}
            $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([a-zA-Z0-9_]+)', $path);
            // Ensure the pattern matches the entire path and is case-insensitive
            $pattern = '#^' . $pattern . '$#i';

            $this->routes[] = [
                'method' => strtoupper($method),
                'path' => $path, // Store original path for parameter extraction
                'pattern' => $pattern,
                'handler' => $handler,
                'middleware' => $middleware
            ];
        }

        /**-------------------------------------------------------------------------*/
        /**
         * Dispatches the incoming request to the appropriate handler, running middleware first.
         *
         * @param HttpRequest $request The incoming HttpRequest object.
         * @param HttpResponse $response The HttpResponse object to build the response.
         * @return void
         */
        /**-------------------------------------------------------------------------*/
        public function dispatch(HttpRequest $req, HttpResponse $res): void{
            /**
             * Define HTTP Request Properties
             */
            $requestMethod   = $req->getMethod();
            $requestPathInfo = $req->getPathInfo();

            foreach ($this->routes as $route) {
                // Check if method matches and path matches the pattern
                if ($route['method'] === $requestMethod && preg_match($route['pattern'], $requestPathInfo, $matches)) {
                    // Remove the full match (index 0) from the matches array
                    array_shift($matches);

                    // Extract parameter names from the original path
                    preg_match_all('/\{([a-zA-Z0-9_]+)\}/', $route['path'], $paramNames);
                    $paramNames = $paramNames[1];

                    // Combine parameter names with extracted values
                    $params = array_combine($paramNames, $matches);

                    // Prepare the full middleware chain including the route handler as the last step
                    $middlewareChain = array_merge($this->middleware, $route['middleware'] ?? []);
                    
                    // Create the final callable for the route handler
                    $finalHandler = function(HttpRequest $req, HttpResponse $res) use ($route, $params) {
                        if (is_callable($route["handler"])) {
                             call_user_func($route['handler'], $req, $res, $params);
                        } elseif (is_string($route["handler"]) || is_array($route["handler"])) {
                            try {
                                if (is_string($route["handler"])) {
                                    $pos = strpos($route["handler"], "@");
                                    if(!is_int($pos)){
                                        throw new \Exception("Unable to determine path!");
                                    }
                                    $handler = [
                                        "className" => substr($route["handler"], 0, $pos),
                                        "methodName" => substr($route["handler"], $pos + 1),
                                    ];
                                } else {
                                    $handler = [
                                        "className" => $route["handler"][0],
                                        "methodName" => $route["handler"][1]
                                    ];
                                }
                                $instance = $this->container->get($handler["className"]);
                                call_user_func_array(
                                    [$instance, $handler["methodName"]],
                                    [$req, $res, $params]
                                );
                            } catch (\Exception $e) {
                                $this->sendErrorResponse($res, $e);
                            }
                        }
                    };

                    // Execute the middleware chain
                    $this->processMiddleware($req, $res, $middlewareChain, $finalHandler);
                    return;
                }
            }

            /**
             * Route Not Found
             */
            $this->sendErrorResponse($res, "Could not find matching route path!");
        }

        /**-------------------------------------------------------------------------*/
        /**
         * Recursively processes the middleware chain.
         *
         * @param HttpRequest $req The incoming HttpRequest object.
         * @param HttpResponse $res The HttpResponse object to build the response.
         * @param array $middlewareChain The array of middleware to process.
         * @param callable $finalHandler The final route handler.
         */
        /**-------------------------------------------------------------------------*/
        private function processMiddleware(HttpRequest $req, HttpResponse $res, array $middlewareChain, callable $finalHandler): void{
            // Check if empty
            if (empty($middlewareChain)) {
                // No more middleware, execute the final handler
                call_user_func($finalHandler, $req, $res);
                return;
            }

            $currentMiddleware = array_shift($middlewareChain);
            $next = function() use ($req, $res, $middlewareChain, $finalHandler) {
                $this->processMiddleware($req, $res, $middlewareChain, $finalHandler);
            };

            // Resolve and execute the current middleware
            try {
                if (is_string($currentMiddleware)) {
                    $middlewareInstance = $this->container->get($currentMiddleware);
                    if (is_callable($middlewareInstance)) {
                        call_user_func($middlewareInstance, $req, $res, $next);
                    } else {
                        // Assume it's a class with an __invoke() method
                        $middlewareInstance($req, $res, $next);
                    }
                } elseif (is_array($currentMiddleware)) {
                    $className = $currentMiddleware[0];
                    $methodName = $currentMiddleware[1];
                    $middlewareInstance = $this->container->get($className);
                    call_user_func_array([$middlewareInstance, $methodName], [$req, $res, $next]);
                } elseif (is_callable($currentMiddleware)) {
                    call_user_func($currentMiddleware, $req, $res, $next);
                } else {
                    $next(); // Skip if handler is not valid
                }
            } catch (\Exception $e) {
                $this->sendErrorResponse($res, $e);
            }
        }

        /**-------------------------------------------------------------------------*/
        /**
         * HTTP Method
         * Sends error response
         */
        /**-------------------------------------------------------------------------*/
        private function sendErrorResponse(HttpResponse $res, string|\Exception $e): void{
            /**
             * Form and execute response 
             */
            $res->setStatusCode(404, 'Not Found');
            $res->addHeader('Content-Type', 'application/json');
            
            $errorMessage = '';
            if ($e instanceof \Exception) {
                $errorMessage = $e->getMessage();
            } else {
                $errorMessage = (string) $e;
            }

            $res->setBody(json_encode([
                "message" => "404 Not Found: The requested resource could not be found",
                "error" => $errorMessage
            ]));
            $res->send();
        }
    }

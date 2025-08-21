<?php

    namespace mnaatjes\mvcFramework\MVCCore;
    use mnaatjes\mvcFramework\DataAccess\BaseRepository;
    use mnaatjes\mvcFramework\HttpCore\HttpRequest;
    use mnaatjes\mvcFramework\HttpCore\HttpResponse;

    /**------------------------------------------------------------------------*/
    /**
     * Abstract Base Controller Class
     *
     * This class provides a foundational structure for all application controllers,
     * enforcing a consistent interface for handling common web requests. It uses
     * dependency injection to receive a repository instance, facilitating data
     * interactions without tightly coupling the controller to a specific data source.
     *
     * Child classes must implement the abstract methods, such as `index()`, `show()`,
     * and `store()`, to define the specific logic for each resource. This design
     * promotes the separation of concerns, keeping the controller focused on
     * orchestrating the flow between the Model and the View.
     *
     * @since 1.0.0
     * - Created abstract class with repository dependency.
     * - Added common HTTP action methods (`index`, `show`, `store`, etc.).
     * - Included helper methods for redirects and JSON responses.
     *
     * @since 1.0.0:
     * - Removed (decoupled) need for BaseRepository Class
     * - Refactored __construct() method:
     *      -> no longer requires BaseRepository
     *      -> accepts optional services array (default []) and parses
     *      -> TODO: Make repositories available thru services
     *      -> uses registerServices() method if services are passed (services are optional)
     * 
     * - Added registerServices() method to store service (short-names) in an array
     * - added _call() method to pull Service objects from $this->services array
     * 
     * @version 1.1.0
     */
    /**------------------------------------------------------------------------*/
    abstract class BaseController {

        /**
         * A container for registered service objects to prevent dynamic property deprecation.
         * @var array Service for interfacing with repository
         */
        protected array $services;

        /**-------------------------------------------------------------------------*/
        /**
         * Constructor
         */
        /**-------------------------------------------------------------------------*/
        public function __construct(object ...$services){

            // Register Services if they exist
            if(!empty($services)){
                $this->registerServices(...$services);
            }
        }

        /**------------------------------------------------------------------------*/
        /**
         * Register Services
         *
         * This method takes a list of objects and assigns them to the
         * controller instance using their short class name as the property name.
         * For example, a `SomeClassService` object will be available as
         * `$this->SomeClassService`.
         *
         * @param object ...$services The service objects to register.
         */
        /**------------------------------------------------------------------------*/
        private function registerServices(object ...$services){
            foreach ($services as $service) {
                // Get the class name of the service, e.g., 'SomeService'
                $className = (new \ReflectionClass($service))->getShortName();

                // Store the service in the dedicated services array.
                $this->services[$className] = $service;
            }
        }

        /**
         * Magic method to allow accessing services as if they were properties.
         *
         * This method is automatically called when you try to access a property
         * that is not explicitly declared. It retrieves the service from the
         * internal `$services` array. This allows for clean syntax like
         * `$this->SomeClassService`.
         *
         * @param string $name The name of the property (service) being accessed.
         * @return object|null The service object or null if not found.
         */
        public function __get(string $name){
            return $this->services[$name] ?? null;
        }

        /**------------------------------------------------------------------------*/
        /**
         * Displays a listing of the resource.
         *
         * @return void
         */
        /**------------------------------------------------------------------------*/
        abstract public function index(HttpRequest $req, HttpResponse $res): void;

        /**------------------------------------------------------------------------*/
        /**
         * Displays the specified resource.
         *
         * @param int $id The unique identifier of the resource.
         * @return void
         */
        /**------------------------------------------------------------------------*/
        abstract public function show(HttpRequest $req, HttpResponse $res, array $params): void;

        /**------------------------------------------------------------------------*/
        /**
         * Shows the form for creating a new resource.
         *
         * @return void
         */
        /**------------------------------------------------------------------------*/
        abstract public function create(HttpRequest $req, HttpResponse $res, array $params): void;

        /**------------------------------------------------------------------------*/
        /**
         * Stores a newly created resource in the database.
         *
         * @return void
         */
        /**------------------------------------------------------------------------*/
        abstract public function store(HttpRequest $req, HttpResponse $res, array $params): void;

        /**------------------------------------------------------------------------*/
        /**
         * Shows the form for editing the specified resource.
         *
         * @param int $id The unique identifier of the resource.
         * @return void
         */
        /**------------------------------------------------------------------------*/
        abstract public function edit(HttpRequest $req, HttpResponse $res, array $params): void;

        /**------------------------------------------------------------------------*/
        /**
         * Updates the specified resource in the database.
         *
         * @param int $id The unique identifier of the resource.
         * @return void
         */
        /**------------------------------------------------------------------------*/
        abstract public function update(HttpRequest $req, HttpResponse $res, array $params): void;

        /**------------------------------------------------------------------------*/
        /**
         * Removes the specified resource from the database.
         *
         * @param int $id The unique identifier of the resource.
         * @return void
         */
        /**------------------------------------------------------------------------*/
        abstract public function destroy(HttpRequest $req, HttpResponse $res, array $params): void;
        
        /**------------------------------------------------------------------------*/
        /**
         * Redirects the user to a specified URI.
         *
         * This is a simple helper method to perform an HTTP redirect.
         *
         * @param string $uri The URI to redirect to.
         * @return void
         */
        /**------------------------------------------------------------------------*/
        protected function redirect(HttpRequest $req, HttpResponse $res, array $params): void {}

        /**------------------------------------------------------------------------*/
        /**
         * Renders a JSON response.
         *
         * This helper method sets the appropriate HTTP header and echoes the JSON
         * encoded data, providing a clean way to send API responses.
         *
         * @param array $data The data to be encoded as JSON.
         * @param int $statusCode The HTTP status code (e.g., 200, 404).
         * @return void
         */
        /**------------------------------------------------------------------------*/
        protected function jsonResponse(HttpRequest $req, HttpResponse $res, array $data): void {}
    }
?>
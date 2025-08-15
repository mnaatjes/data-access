<?php

    namespace mnaatjes\App\MVCCore;
    use mnaatjes\App\DataAccess\BaseRepository;
    use mnaatjes\App\HttpCore\HttpRequest;
    use mnaatjes\App\HttpCore\HttpResponse;

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
     * @version 1.0.0
     */
    /**------------------------------------------------------------------------*/
    abstract class BaseController {
        /**
         * The repository instance for accessing data.
         *
         * @var BaseRepository
         */
        protected BaseRepository $repository;

        /**------------------------------------------------------------------------*/
        /**
         * Initializes the controller with a repository instance.
         *
         * This constructor uses dependency injection to set the repository, making
         * the controller testable and loosely coupled from its data source. It also
         * captures the incoming request data for use in action methods.
         *
         * @param BaseRepository $repository The repository for this controller's resource.
         * @param array $requestData An array containing all incoming request data.
         */
        /**------------------------------------------------------------------------*/
        public function __construct(){

            // Validate BaseRepository is set
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
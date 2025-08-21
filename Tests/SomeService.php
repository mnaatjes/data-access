<?php
    namespace mnaatjes\mvcFramework\Tests;

use mnaatjes\mvcFramework\DataAccess\BaseRepository;

    /**
     * Test Service
     */
    class SomeService {

        public array $data=[];

        public BaseRepository $repo;
        /**
         * Constructor
         */
        public function __construct(BaseRepository $repo){
            $this->repo = $repo;
        }
        /**
         * SayHello
         */
        public function sayHello(){echo "Hello World";}
        public function store($key, $value){$this->data[$key] = $value;}
        public function read($key){var_dump($this->data[$key]);}
    }
?>
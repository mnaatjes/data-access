<?php
    namespace mnaatjes\App\Utils;
    use mnaatjes\App\DataAccess\BaseRepository;

    /**-------------------------------------------------------------------------*/
    /**
     * TestRepository uses Absract BaseRepository
     */
    /**-------------------------------------------------------------------------*/
    class TestRepository extends BaseRepository {

        protected string $tableName     = "orders";
        protected string $modelClass    = TestModel::class;

        /**-------------------------------------------------------------------------*/
        /**
         * 
         */
        /**-------------------------------------------------------------------------*/

        /**-------------------------------------------------------------------------*/
    }
?>
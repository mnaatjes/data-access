<?php
    namespace mnaatjes\mvcFramework\Tests;
    use mnaatjes\mvcFramework\DataAccess\BaseRepository;

    /**-------------------------------------------------------------------------*/
    /**
     * TestRepository uses Absract BaseRepository
     */
    /**-------------------------------------------------------------------------*/
    class SomeRepository extends BaseRepository {

        protected string $tableName     = "test";
        protected string $modelClass    = SomeModel::class;

    }
?>
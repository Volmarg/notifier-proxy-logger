<?php


namespace App\Repository;


interface CrudOperationsRepositoryInterface
{
    // todo: check if in php8 i can solve this somehow to require implementation but with attribute of custom class

    public function findOneById();

    public function deleteEntity();

    public function saveEntity();
}
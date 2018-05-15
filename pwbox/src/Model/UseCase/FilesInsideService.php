<?php
/**
 * Created by PhpStorm.
 * User: victo
 * Date: 02/05/2018
 * Time: 18:58
 */
namespace pwbox\Model\UseCase;

use pwbox\Model\UserRepository;
use pwbox\Model\User;


class FilesInsideService{
    private $repository;

    public function __construct(UserRepository $repository){
        $this->repository = $repository;
    }

    public function __invoke(array $rawData)
    {
        return $this->repository->loadFiles($rawData["id"]);
    }
}
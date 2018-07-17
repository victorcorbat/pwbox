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


class UpdateService{
    private $repository;

    public function __construct(UserRepository $repository){
        $this->repository = $repository;
    }

    public function updatePass(array $rawData)
    {
        return $this->repository->updatePass($rawData);
    }

    public function updateData(array $rawData)
    {
        $now = new \Datetime('now');
        $user = new User(
            $rawData['id'],
            null,
            $rawData['email'],
            null,
            null,
            null,
            $now
        );

        return $this->repository->updateData($user);
    }
}
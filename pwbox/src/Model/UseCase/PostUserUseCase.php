<?php
/**
 * Created by PhpStorm.
 * User: victo
 * Date: 12/04/2018
 * Time: 18:55
 */
namespace pwbox\Model\UseCase;

use pwbox\Model\UserRepository;
use pwbox\Model\User;


class PostUserUseCase{
    private $repository;
    /** @var UserRepository $repository */

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }
    public function __invoke(array $rawData)
    {
        $now = new \Datetime('now');
        $user = new User(
            null,
            $rawData['username'],
            $rawData['email'],
            $rawData['birthdate'],
            $rawData['password'],
            $now,
            $now
        );
        return $this->repository->save($user);
    }
}

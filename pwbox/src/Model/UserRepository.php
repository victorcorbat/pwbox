<?php
/**
 * Created by PhpStorm.
 * User: victo
 * Date: 12/04/2018
 * Time: 18:52
 */

namespace pwbox\Model;


interface UserRepository
{
    public function save(User $user);

    public function login($username, $password);
}
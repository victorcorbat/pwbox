<?php
/**
 * Created by PhpStorm.
 * User: victo
 * Date: 12/04/2018
 * Time: 18:45
 */

namespace pwbox\Model;


class User
{
    private $id;
    /** @var int $id */
    private $username;
    /** @var string $username */
    private $email;

    private $birthdate;
    /** @var string $email */
    private $password;
    /** @var \DateTime $updatedAt */
    private $createdAt;
    /** @var \DateTime $createdAt */
    private $updatedAt;


    public function __construct(
        $id,
        $username,
        $email,
        $birthdate,
        $password,
        $createdAt,
        $updatedAt
    )
    {
        $this->id = $id;
        $this->username = $username;
        $this->email = $email;
        $this->birthdate = $birthdate;
        $this->password = $password;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    public function getId(): int {
        return $this->id;
    }

    public function getUsername(): string {
        return $this->username;
    }

    public function getEmail(): string {
        return $this->email;
    }

    public function getPassword(): string {
        return $this->password;
    }

    public function getCreatedAt(): \DateTime {
        return $this->createdAt;
    }

    public function getUpdatedAt(): \DateTime {
        return $this->updatedAt;
    }

    public function getBirthdate(): string {
        return $this->birthdate;
    }

}
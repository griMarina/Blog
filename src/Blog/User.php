<?php

namespace Grimarina\Blog_Project\Blog;

use Grimarina\Blog_Project\Blog\UUID;

class User 
{
   
    public function __construct(
        private UUID $uuid,
        private string $username,
        private string $hashedPassword,
        private string $firstname, 
        private string $lastname
    ) 
    {
    }

    public function getUuid(): UUID
    {
        return $this->uuid;
    }

    public function getHashedPassword(): string
    {
        return $this->hashedPassword;
    }

    private static function hash(string $password, UUID $uuid): string
    {
        return hash('sha256', $uuid . $password);
    }

    public function checkPassword(string $password): bool 
    {
        return $this->hashedPassword === self::hash($password, $this->uuid); 
    }

    public static function createFrom(string $username, string $password, string $firstname, string $lastname): self 
    {
        $uuid = UUID::random();

        return new self(
            $uuid,
            $username, 
            self::hash($password, $uuid), 
            $firstname,
            $lastname
        );
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getFirstname(): string
    {
        return $this->firstname;
    }

    public function getLastname(): string
    {                
        return $this->lastname;
    }

    public function __toString(): string
    {
        return "User: " . $this->getFirstname() . " " . $this->getLastname() . PHP_EOL;
    }
    
    
}
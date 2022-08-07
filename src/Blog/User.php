<?php

namespace Grimarina\Blog_Project\Blog;

use Grimarina\Blog_Project\Blog\UUID;

class User 
{
   
    public function __construct(
        private UUID $uuid,
        private string $username,
        private string $firstname, 
        private string $lastname
    ) 
    {
    }

    public function getUuid(): UUID
    {
        return $this->uuid;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getFirstname()
    {
        return $this->firstname;
    }

    public function getLastname()
    {                
        return $this->lastname;
    }

    public function __toString(): string
    {
        return "User: " . $this->getFirstname() . " " . $this->getLastname() . PHP_EOL;
    }
    
}
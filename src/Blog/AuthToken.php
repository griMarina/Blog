<?php

namespace Grimarina\Blog_Project\Blog;

use DateTimeImmutable;
use Grimarina\Blog_Project\Blog\UUID;

class AuthToken
{
    public function __construct(
        private string $token,
        private UUID $userUuid,
        private DateTimeImmutable $expiresOn
    )
    {
    }
    public function token(): string 
    {
        return $this->token; 
    }
        
    public function userUuid(): UUID 
    {
        return $this->userUuid; 
    }
        
    public function expiresOn(): DateTimeImmutable 
    {
        return $this->expiresOn; 
    }
}
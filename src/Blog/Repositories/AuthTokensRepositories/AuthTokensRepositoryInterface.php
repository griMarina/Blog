<?php

namespace Grimarina\Blog_Project\Blog\Repositories\AuthTokensRepositories;

use Grimarina\Blog_Project\Blog\AuthToken;

interface AuthTokensRepositoryInterface
{
    public function save(AuthToken $authToken): void;
    
    public function get(string $token): AuthToken;
}
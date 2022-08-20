<?php

namespace Grimarina\Blog_Project\http\Auth;

use Grimarina\Blog_Project\Blog\Repositories\UsersRepositories\UsersRepositoryInterface;
use Grimarina\Blog_Project\Blog\User;
use Grimarina\Blog_Project\Exceptions\{AuthException, HttpException, UserNotFoundException};
use Grimarina\Blog_Project\http\Request;
use Grimarina\Blog_Project\http\Auth\IdentificationInterface;

class JsonBodyUsernameIdentification implements IdentificationInterface
{
    public function __construct(
        private UsersRepositoryInterface $usersRepository
    )
    {
    }

    public function user(Request $request): User
    {
        try {
            $username = $request->jsonBodyField('username');
        } catch (HttpException $exception) {
            throw new AuthException ($exception->getMessage());
        }
        
        try {
           return $this->usersRepository->getByUsername($username);
        } catch (UserNotFoundException $exception) {
            throw new AuthException($exception->getMessage());
        }
    }
}
<?php

namespace Grimarina\Blog_Project\http\Auth;

use Grimarina\Blog_Project\Blog\Repositories\UsersRepositories\UsersRepositoryInterface;
use Grimarina\Blog_Project\Blog\User;
use Grimarina\Blog_Project\Exceptions\{AuthException, HttpException, UserNotFoundException};
use Grimarina\Blog_Project\http\Auth\AuthenticationInterface;
use Grimarina\Blog_Project\http\Request;

class PasswordAuthentication implements PasswordAuthenticationInterface
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
        } catch (HttpException $error) {
            throw new AuthException($error->getMessage());
        }

        try {
            $user = $this->usersRepository->getByUsername($username);
        } catch (UserNotFoundException $error) {
            throw new AuthException($error->getMessage());
        }
    
        try {
            $password = $request->jsonBodyField('password');
        } catch (HttpException $error) {
            throw new AuthException($error->getMessage());
        }
        
        if (!$user->checkPassword($password)) {
            throw new AuthException('Wrong password');
        }
            
        return $user;
    }
}
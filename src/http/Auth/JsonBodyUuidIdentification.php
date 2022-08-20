<?php

namespace Grimarina\Blog_Project\http\Auth;


use Grimarina\Blog_Project\Blog\Repositories\UsersRepositories\UsersRepositoryInterface;
use Grimarina\Blog_Project\Blog\{User, UUID};
use Grimarina\Blog_Project\Exceptions\{AuthException, HttpException, InvalidArgumentException, UserNotFoundException};
use Grimarina\Blog_Project\http\Request;
use Grimarina\Blog_Project\http\Auth\IdentificationInterface;

class JsonBodyUuidIdentification implements IdentificationInterface
{
    public function __construct(
        private UsersRepositoryInterface $userRepository
    )
    { 
    }

    public function user(Request $request): User
    {
        try {
            $userUuid = new UUID($request->jsonBodyField('author_uuid'));
            
        } catch (HttpException | InvalidArgumentException $exception) {
            throw new AuthException($exception->getMessage());
        }

        try {
            return $this->userRepository->get($userUuid);
        } catch (UserNotFoundException $exception) {
            throw new AuthException($exception->getMessage());
        }
    }
}
<?php

namespace Grimarina\Blog_Project\http\Actions\Users;

use Grimarina\Blog_Project\Blog\Repositories\UsersRepositories\UsersRepositoryInterface;
use Grimarina\Blog_Project\Blog\{User, UUID};
use Grimarina\Blog_Project\Exceptions\HttpException;
use Grimarina\Blog_Project\http\Actions\ActionInterface;
use Grimarina\Blog_Project\http\{ErrorResponse, Request, Response, SuccessfulResponse};
use Psr\Log\LoggerInterface;

class CreateUser implements ActionInterface 
{
    public function __construct(
        private UsersRepositoryInterface $usersRepository,
        private LoggerInterface $logger
    )
    {
    }

    public function handle(Request $request): Response
    {
        try {
            $newUserUuid = UUID::random();

            $user = new User(
                $newUserUuid,
                $request->jsonBodyField('username'),
                $request->jsonBodyField('firstname'),
                $request->jsonBodyField('lastname')
            );

        } catch (HttpException $error) {
            return new ErrorResponse($error->getMessage());
        }

        $this->usersRepository->save($user);

        $this->logger->info('User ' . $user->getUsername() . ' created');

        return new SuccessfulResponse([
            'uuid' => (string)$newUserUuid,
        ]);
    }

}
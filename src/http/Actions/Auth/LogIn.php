<?php

namespace Grimarina\Blog_Project\http\Actions\Auth;

use DateTimeImmutable;
use Grimarina\Blog_Project\Blog\AuthToken;
use Grimarina\Blog_Project\Blog\Repositories\AuthTokensRepositories\AuthTokensRepositoryInterface;
use Grimarina\Blog_Project\Exceptions\AuthException;
use Grimarina\Blog_Project\http\Actions\ActionInterface;
use Grimarina\Blog_Project\http\Auth\PasswordAuthenticationInterface;
use Grimarina\Blog_Project\http\{ErrorResponse, Request, Response, SuccessfulResponse};

class LogIn implements ActionInterface
{
    public function __construct(
        private PasswordAuthenticationInterface  $passwordAuthentication,
        private AuthTokensRepositoryInterface $authTokensRepository,
    )
    {    
    }

    public function handle(Request $request): Response
    {
        try {
            $user = $this->passwordAuthentication->user($request);
        } catch (AuthException $error) {
            return new ErrorResponse($error->getMessage());
        }
        
        $authToken = new AuthToken(
            bin2hex(random_bytes(40)),
            $user->getUuid(),
            (new DateTimeImmutable())->modify('+1 day')
        );

        $this->authTokensRepository->save($authToken);

        return new SuccessfulResponse([
            'token' => $authToken->token(),
        ]);
    }
}
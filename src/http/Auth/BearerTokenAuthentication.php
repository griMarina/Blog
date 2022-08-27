<?php

namespace Grimarina\Blog_Project\http\Auth;

use DateTimeImmutable;
use Grimarina\Blog_Project\Blog\Repositories\AuthTokensRepositories\AuthTokensRepositoryInterface;
use Grimarina\Blog_Project\Blog\Repositories\UsersRepositories\UsersRepositoryInterface;
use Grimarina\Blog_Project\Blog\User;
use Grimarina\Blog_Project\Exceptions\{AuthException, AuthTokenNotFoundException, HttpException};
use Grimarina\Blog_Project\http\Request;
use Grimarina\Blog_Project\http\Auth\TokenAuthenticationInterface;

class BearerTokenAuthentication implements TokenAuthenticationInterface
{
    private const HEADER_PREFIX = 'Bearer ';

    public function __construct(
        private AuthTokensRepositoryInterface $authTokensRepository,
        private UsersRepositoryInterface $usersRepository,
    )
    {   
    }

    public function user(Request $request): User
    {
        try {
            $header = $request->header('Authorization');
        } catch (HttpException $error) {
            throw new AuthException($error->getMessage());
        }

        if (!str_starts_with($header, self::HEADER_PREFIX)) { 
            throw new AuthException("Malformed token: [$header]");
        }

        $token = mb_substr($header, strlen(self::HEADER_PREFIX));
      
        try {
            $authToken = $this->authTokensRepository->get($token);
        } catch (AuthTokenNotFoundException) {
            throw new AuthException("Bad token: [$token]");
        }

        if ($authToken->expiresOn() <= new DateTimeImmutable()) { 
            throw new AuthException("Token expired: [$token]");
        }

        $userUuid = $authToken->userUuid();

        return $this->usersRepository->get($userUuid);
    }
}
<?php

namespace Grimarina\Blog_Project\http\Actions\Auth;

use DateTimeImmutable;
use Grimarina\Blog_Project\Blog\AuthToken;
use Grimarina\Blog_Project\Blog\Repositories\AuthTokensRepositories\AuthTokensRepositoryInterface;
use Grimarina\Blog_Project\Exceptions\{AuthException, AuthTokenNotFoundException, HttpException};
use Grimarina\Blog_Project\http\Actions\ActionInterface;
use Grimarina\Blog_Project\http\{Request, Response, SuccessfulResponse};

class LogOut implements ActionInterface
{
    private const HEADER_PREFIX = 'Bearer ';

    public function __construct(
        private AuthTokensRepositoryInterface $authTokensRepository,
    )
    {   
    }
    
    public function handle(Request $request): Response
    {
        try {
                $header = $request->header('Authorization');
        } catch (HttpException $error) {
                throw new AuthException($error->getMessage());
        }
            
        $token = mb_substr($header, strlen(self::HEADER_PREFIX));
          
        try {
                $oldToken = $this->authTokensRepository->get($token);
        } catch (AuthTokenNotFoundException) {
                throw new AuthException("Bad token: [$token]");
        }   

        $newToken = new AuthToken(
            $oldToken->token(),
            $oldToken->userUuid(),            
            new DateTimeImmutable("now"),      
        );

       $this->authTokensRepository->save($newToken);

       return new SuccessfulResponse([
            'token' => $newToken->token(),
        ]); 
    }
}
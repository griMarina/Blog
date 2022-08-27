<?php

namespace Grimarina\Blog_Project\http\Actions\Posts;

use Grimarina\Blog_Project\Blog\Repositories\PostsRepositories\PostsRepositoryInterface;
use Grimarina\Blog_Project\Blog\{Post, UUID};
use Grimarina\Blog_Project\Exceptions\AuthException;
use Grimarina\Blog_Project\Exceptions\HttpException;
use Grimarina\Blog_Project\http\Actions\ActionInterface;
use Grimarina\Blog_Project\http\{ErrorResponse, Request, Response, SuccessfulResponse};
use Grimarina\Blog_Project\http\Auth\TokenAuthenticationInterface;

class CreatePost implements ActionInterface 
{
    public function __construct(
        private PostsRepositoryInterface $postsRepository,
        private TokenAuthenticationInterface $authentication,
    )
    {
    }

    public function handle(Request $request): Response
    {

        try {
            $author = $this->authentication->user($request); 
        } catch (AuthException $error) {
            return new ErrorResponse($error->getMessage()); 
        }

        $newPostUuid = UUID::random();

        try {

            $post = new Post(
                $newPostUuid,
                $author->getUuid(),
                $request->jsonBodyField('title'),
                $request->jsonBodyField('text')
            );

        } catch (HttpException $error) {
            return new ErrorResponse($error->getMessage());
        }

        $this->postsRepository->save($post);

        return new SuccessfulResponse([
            'uuid' => (string)$newPostUuid,
        ]);
    }

}
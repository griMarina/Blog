<?php

namespace Grimarina\Blog_Project\http\Actions\Posts;

use Grimarina\Blog_Project\Blog\Repositories\PostsRepositories\PostsRepositoryInterface;
use Grimarina\Blog_Project\Exceptions\{HttpException, PostNotFoundException};
use Grimarina\Blog_Project\Blog\UUID;
use Grimarina\Blog_Project\http\Actions\ActionInterface;
use Grimarina\Blog_Project\http\{ErrorResponse, Request, Response, SuccessfulResponse};


class DeletePost implements ActionInterface
{
    public function __construct(
        private PostsRepositoryInterface $postsRepository
    )
    {
    }

    public function handle(Request $request): Response
    {
        try {
            $postUuid = $request->query('uuid');
        } catch (HttpException $error) {
            return new ErrorResponse($error->getMessage());
        }

        try {
            
            $this->postsRepository->delete(new UUID($postUuid));

        } catch (PostNotFoundException $error) {
            return new ErrorResponse($error->getMessage());
        }

        return new SuccessfulResponse([
            'uuid' => (string)$postUuid,
        ]);
    }
}
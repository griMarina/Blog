<?php

namespace Grimarina\Blog_Project\http\Actions\Likes;

use Grimarina\Blog_Project\Blog\Repositories\LikesRepositories\LikesRepositoryInterface;
use Grimarina\Blog_Project\Exceptions\{HttpException, PostNotFoundException};
use Grimarina\Blog_Project\Blog\UUID;
use Grimarina\Blog_Project\http\Actions\ActionInterface;
use Grimarina\Blog_Project\http\{ErrorResponse, Request, Response, SuccessfulResponse};


class FindByPostUuid implements ActionInterface
{
    public function __construct(
        private LikesRepositoryInterface $likesRepository
    )
    {
    }

    public function handle(Request $request): Response
    {
        try {
            $postUuid = $request->query('post_uuid');
        } catch (HttpException $error) {
            return new ErrorResponse($error->getMessage());
        }

        try {
            $likes = $this->likesRepository->getByPostUuid(new UUID($postUuid));

        } catch (PostNotFoundException $error) {
            return new ErrorResponse($error->getMessage());
        }

        return new SuccessfulResponse($likes);
    }
}
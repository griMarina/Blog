<?php

namespace Grimarina\Blog_Project\http\Actions\Posts;

use Grimarina\Blog_Project\Blog\Repositories\PostsRepositories\PostsRepositoryInterface;
use Grimarina\Blog_Project\Exceptions\{UserNotFoundException, HttpException};
use Grimarina\Blog_Project\Blog\UUID;
use Grimarina\Blog_Project\http\Actions\ActionInterface;
use Grimarina\Blog_Project\http\{ErrorResponse, Request, Response, SuccessfulResponse};


class FindByUuid implements ActionInterface
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
            $post = $this->postsRepository->get(new UUID($postUuid));

        } catch (UserNotFoundException $error) {
            return new ErrorResponse($error->getMessage());
        }

        return new SuccessfulResponse([
            'author_uuid' => (string)$post->getAuthor_uuid(),
            'title' => $post->getTitle(),
            'text' => $post->getText()
        ]);
    }
}
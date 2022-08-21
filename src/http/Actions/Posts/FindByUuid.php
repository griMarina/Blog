<?php

namespace Grimarina\Blog_Project\http\Actions\Posts;

use Grimarina\Blog_Project\Blog\Repositories\PostsRepositories\PostsRepositoryInterface;
use Grimarina\Blog_Project\Exceptions\{HttpException, PostNotFoundException};
use Grimarina\Blog_Project\Blog\UUID;
use Grimarina\Blog_Project\http\Actions\ActionInterface;
use Grimarina\Blog_Project\http\{ErrorResponse, Request, Response, SuccessfulResponse};
use Psr\Log\LoggerInterface;

class FindByUuid implements ActionInterface
{
    public function __construct(
        private PostsRepositoryInterface $postsRepository,
        private LoggerInterface $logger,
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

        } catch (PostNotFoundException $error) {
            $this->logger->warning($error->getMessage()); 
            return new ErrorResponse($error->getMessage());        
        }

        return new SuccessfulResponse([
            'author_uuid' => (string)$post->getAuthor_uuid(),
            'title' => $post->getTitle(),
            'text' => $post->getText()
        ]);
    }
}
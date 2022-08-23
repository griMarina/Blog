<?php

namespace Grimarina\Blog_Project\http\Actions\Posts;

use Grimarina\Blog_Project\Blog\Repositories\PostsRepositories\PostsRepositoryInterface;
use Grimarina\Blog_Project\Blog\{Post, UUID};
use Grimarina\Blog_Project\Exceptions\HttpException;
use Grimarina\Blog_Project\http\Actions\ActionInterface;
use Grimarina\Blog_Project\http\{ErrorResponse, Request, Response, SuccessfulResponse};
use Grimarina\Blog_Project\http\Auth\IdentificationInterface;

class CreatePost implements ActionInterface 
{
    public function __construct(
        private PostsRepositoryInterface $postsRepository,
        private IdentificationInterface $identification,
    )
    {
    }

    public function handle(Request $request): Response
    {
        $author = $this->identification->user($request);

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
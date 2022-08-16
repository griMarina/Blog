<?php

namespace Grimarina\Blog_Project\http\Actions\Posts;

use Grimarina\Blog_Project\Blog\Repositories\PostsRepositories\PostsRepositoryInterface;
use Grimarina\Blog_Project\Blog\{Post, UUID};
use Grimarina\Blog_Project\Blog\Repositories\UsersRepositories\UsersRepositoryInterface;
use Grimarina\Blog_Project\Exceptions\{HttpException, InvalidArgumentException, UserNotFoundException};
use Grimarina\Blog_Project\http\Actions\ActionInterface;
use Grimarina\Blog_Project\http\{ErrorResponse, Request, Response, SuccessfulResponse};

class CreatePost implements ActionInterface 
{
    public function __construct(
        private PostsRepositoryInterface $postsRepository,
        private UsersRepositoryInterface $usersRepository,
    )
    {
    }

    public function handle(Request $request): Response
    {
        try {
            $authorUuid = new UUID($request->jsonBodyField('author_uuid'));
        } catch (HttpException | InvalidArgumentException $error) { 
            return new ErrorResponse($error->getMessage());
        }
        try { 
            $this->usersRepository->get($authorUuid);
        } catch (UserNotFoundException $error) {
            return new ErrorResponse($error->getMessage());
        }

        try {
            $newPostUuid = UUID::random();

            $post = new Post(
                $newPostUuid,
                $authorUuid,
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
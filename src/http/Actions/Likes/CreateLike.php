<?php

namespace Grimarina\Blog_Project\http\Actions\Likes;

use Grimarina\Blog_Project\Blog\Repositories\PostsRepositories\PostsRepositoryInterface;
use Grimarina\Blog_Project\Blog\{Like, UUID};
use Grimarina\Blog_Project\Blog\Repositories\LikesRepositories\LikesRepositoryInterface;
use Grimarina\Blog_Project\Blog\Repositories\UsersRepositories\UsersRepositoryInterface;
use Grimarina\Blog_Project\Exceptions\{HttpException, InvalidArgumentException, LikeAlreadyExistsException, UserNotFoundException, PostNotFoundException};
use Grimarina\Blog_Project\http\Actions\ActionInterface;
use Grimarina\Blog_Project\http\{ErrorResponse, Request, Response, SuccessfulResponse};

class CreateLike implements ActionInterface 
{
    public function __construct(
        private LikesRepositoryInterface $likesRepository,
        private PostsRepositoryInterface $postsRepository,
        private UsersRepositoryInterface $usersRepository,
    )
    {
    }

    public function handle(Request $request): Response
    {
        try {
            $postUuid = new UUID($request->jsonBodyField('post_uuid'));
        } catch (HttpException | InvalidArgumentException $error) { 
            return new ErrorResponse($error->getMessage());
        }
        try { 
            $this->postsRepository->get($postUuid);
        } catch (PostNotFoundException $error) {
            return new ErrorResponse($error->getMessage());
        }

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
            $this->likesRepository->isLikeAlreadyExists($postUuid, $authorUuid);
        } catch (LikeAlreadyExistsException $error) {
            return new ErrorResponse($error->getMessage());
        }

        try {
            $newLikeUuid = UUID::random();

            $like = new Like(
                $newLikeUuid,
                $postUuid,
                $authorUuid,
            );


        } catch (HttpException $error) {
            return new ErrorResponse($error->getMessage());
        }

        $this->likesRepository->save($like);

        return new SuccessfulResponse([
            'uuid' => (string)$newLikeUuid,
        ]);
    }

}
<?php

namespace Grimarina\Blog_Project\http\Actions\Likes;

use Grimarina\Blog_Project\Blog\Repositories\PostsRepositories\PostsRepositoryInterface;
use Grimarina\Blog_Project\Blog\{Like, UUID};
use Grimarina\Blog_Project\Blog\Repositories\LikesRepositories\LikesRepositoryInterface;
use Grimarina\Blog_Project\Exceptions\{AuthException, HttpException, InvalidArgumentException, LikeAlreadyExistsException, PostNotFoundException};
use Grimarina\Blog_Project\http\Actions\ActionInterface;
use Grimarina\Blog_Project\http\{ErrorResponse, Request, Response, SuccessfulResponse};
use Grimarina\Blog_Project\http\Auth\TokenAuthenticationInterface;

class CreateLike implements ActionInterface 
{
    public function __construct(
        private LikesRepositoryInterface $likesRepository,
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
            $this->likesRepository->isLikeAlreadyExists($postUuid, $author->getUuid());
        } catch (LikeAlreadyExistsException $error) {
            return new ErrorResponse($error->getMessage());
        }

        try {
            $newLikeUuid = UUID::random();

            $like = new Like(
                $newLikeUuid,
                $postUuid,
                $author->getUuid(),
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
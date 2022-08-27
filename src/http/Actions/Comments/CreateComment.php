<?php

namespace Grimarina\Blog_Project\http\Actions\Comments;

use Grimarina\Blog_Project\Blog\Repositories\PostsRepositories\PostsRepositoryInterface;
use Grimarina\Blog_Project\Blog\{Comment, UUID};
use Grimarina\Blog_Project\Blog\Repositories\CommentsRepositories\CommentsRepositoryInterface;
use Grimarina\Blog_Project\Exceptions\{AuthException, HttpException, InvalidArgumentException, PostNotFoundException};
use Grimarina\Blog_Project\http\Actions\ActionInterface;
use Grimarina\Blog_Project\http\{ErrorResponse, Request, Response, SuccessfulResponse};
use Grimarina\Blog_Project\http\Auth\TokenAuthenticationInterface;

class CreateComment implements ActionInterface 
{
    public function __construct(
        private CommentsRepositoryInterface $commentsRepository,
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
            $newCommentUuid = UUID::random();

            $comment = new Comment(
                $newCommentUuid,
                $postUuid,
                $author->getUuid(),
                $request->jsonBodyField('text')
            );


        } catch (HttpException $error) {
            return new ErrorResponse($error->getMessage());
        }

        $this->commentsRepository->save($comment);

        return new SuccessfulResponse([
            'uuid' => (string)$newCommentUuid,
        ]);
    }

}
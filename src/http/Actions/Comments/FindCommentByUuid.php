<?php

namespace Grimarina\Blog_Project\http\Actions\Comments;

use Grimarina\Blog_Project\Blog\Repositories\CommentsRepositories\CommentsRepositoryInterface;
use Grimarina\Blog_Project\Blog\UUID;
use Grimarina\Blog_Project\Exceptions\{CommentNotFoundException, HttpException};
use Grimarina\Blog_Project\http\{ErrorResponse, Request, Response, SuccessfulResponse};

class FindCommentByUuid 
{
    public function __construct(
        private CommentsRepositoryInterface $commentsRepository,        
    )
    {
    }

    public function handle(Request $request): Response
    {
        try {
            $commenttUuid = $request->query('uuid');
        } catch (HttpException $error) {
            return new ErrorResponse($error->getMessage());
        }

        try {
            $comment = $this->commentsRepository->get(new UUID($commenttUuid));

        } catch (CommentNotFoundException $error) {
            return new ErrorResponse($error->getMessage());          
        }

        return new SuccessfulResponse([
            'post_uuid' => (string)$comment->getPost_uuid(),
            'author_uuid' => (string)$comment->getAuthor_uuid(),
            'text' => $comment->getText()
        ]);
    }
}
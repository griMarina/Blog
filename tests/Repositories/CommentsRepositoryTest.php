<?php

namespace Repositories;

use Grimarina\Blog_Project\Exceptions\CommentNotFoundException;
use Grimarina\Blog_Project\Blog\Repositories\CommentsRepositories\CommentsRepository;
use Grimarina\Blog_Project\Blog\UUID;
use Grimarina\Blog_Project\Blog\Comment;
use Grimarina\Blog_Project\UnitTests\DummyLogger;
use PDO;
use PDOStatement;
use PHPUnit\Framework\TestCase;

class CommentsRepositoryTest extends TestCase 
{

    public function testItSavesCommentToDatabase(): void
    {
        $connectionStub = $this->createStub(PDO::class);
        $statementMock = $this->createMock(PDOStatement::class);

        $statementMock
        ->expects($this->once())
        ->method('execute')
        ->with([
            ':uuid' => 'f440d768-3a0f-41fd-bafc-ed38c16252bc', 
            ':post_uuid' => '9127e521-7ac0-4357-b6c5-b1bcc01ba613',
            ':author_uuid' => '3e00843d-02e5-4837-bba5-a5eb9d33697d',
            ':text' => 'I like this post!',
        ]);

        $connectionStub->method('prepare')->willReturn($statementMock);

        $repository = new CommentsRepository($connectionStub, new DummyLogger());

        $repository->save(new Comment(
            new UUID('f440d768-3a0f-41fd-bafc-ed38c16252bc'),
            new UUID('9127e521-7ac0-4357-b6c5-b1bcc01ba613'),
            new UUID('3e00843d-02e5-4837-bba5-a5eb9d33697d'),
            'I like this post!'
        ));
    }


    public function testItThrowsAnExceptionWhenCommentNotFound(): void
    {
        $connectionMock = $this->createMock(PDO::class);
        $statementStub = $this->createStub(PDOStatement::class);

        $statementStub->method('fetch')->willReturn(false);
        $connectionMock->method('prepare')->willReturn($statementStub);

    
        $repository = new CommentsRepository($connectionMock, new DummyLogger());
        $this->expectException(CommentNotFoundException::class);
        $this->expectExceptionMessage('Cannot find comment: f440d768-3a0f-41fd-bafc-ed38c16252bc');

        $repository->get(new UUID('f440d768-3a0f-41fd-bafc-ed38c16252bc'));
    }
}

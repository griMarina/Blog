<?php

namespace Repositories;

use Grimarina\Blog_Project\Exceptions\PostNotFoundException;
use Grimarina\Blog_Project\Blog\Repositories\PostsRepositories\PostsRepository;
use Grimarina\Blog_Project\Blog\UUID;
use Grimarina\Blog_Project\Blog\Post;
use PDO;
use PDOStatement;
use PHPUnit\Framework\TestCase;

class PostsRepositoryTest extends TestCase 
{

    public function testItSavesPostToDatabase(): void
    {
        $connectionStub = $this->createStub(PDO::class);
        $statementMock = $this->createMock(PDOStatement::class);

        $statementMock
        ->expects($this->once())
        ->method('execute')
        ->with([
            ':uuid' => 'f440d768-3a0f-41fd-bafc-ed38c16252bc', 
            ':author_uuid' => '9127e521-7ac0-4357-b6c5-b1bcc01ba613',
            ':title' => 'My first post',
            ':text' => 'Hello everyone!',
        ]);

        $connectionStub->method('prepare')->willReturn($statementMock);

        $repository = new PostsRepository($connectionStub);

        $repository->save(new Post(
            new UUID('f440d768-3a0f-41fd-bafc-ed38c16252bc'),
            new UUID('9127e521-7ac0-4357-b6c5-b1bcc01ba613'),
            'My first post',
            'Hello everyone!'
        ));
    }

    // public function testItGetsPostFromDatabase(): void
    // {
    //     $connectionStub = $this->createStub(PDO::class);
    //     $statementMock = $this->createMock(PDOStatement::class);

    //     $statementMock
    //     ->expects($this->once())
    //     ->method('execute')
    //     ->with(['uuid' => 'f440d768-3a0f-41fd-bafc-ed38c16252bc']);

    //     $connectionStub->method('prepare')->willReturn($statementMock);

    //     $post = [
    //         ':uuid' => 'f440d768-3a0f-41fd-bafc-ed38c16252bc', 
    //         ':author_uuid' => '9127e521-7ac0-4357-b6c5-b1bcc01ba613',
    //         ':title' => 'My first post',
    //         ':text' => 'Hello everyone!'

    //     ];
      
    //     $statementMock->method('fetch')->willReturn($post);

    //     $this->assertObjectEquals(
    //         new Post(
    //             new UUID('f440d768-3a0f-41fd-bafc-ed38c16252bc'), new UUID('9127e521-7ac0-4357-b6c5-b1bcc01ba613'),
    //             'My first post', 
    //             'Hello everyone!'), 
    //         new Post(
    //             new UUID($post['uuid']),
    //             new UUID($post['author_uuid']), 
    //             $post['title'],
    //             $post['text']), 
    //     );
            
        
    //     $repository = new PostsRepository($connectionStub);          $repository->get(new UUID('f440d768-3a0f-41fd-bafc-ed38c16252bc'));
        
    // }


    public function testItThrowsAnExceptionWhenPostNotFound(): void
    {
        $connectionMock = $this->createMock(PDO::class);
        $statementStub = $this->createStub(PDOStatement::class);

        $statementStub->method('fetch')->willReturn(false);
        $connectionMock->method('prepare')->willReturn($statementStub);

        $repository = new PostsRepository($connectionMock);
        $this->expectException(PostNotFoundException::class);
        $this->expectExceptionMessage('Cannot find post: f440d768-3a0f-41fd-bafc-ed38c16252bc');

        $repository->get(new UUID('f440d768-3a0f-41fd-bafc-ed38c16252bc'));
    }
}

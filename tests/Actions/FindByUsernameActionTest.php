<?php

namespace Actions;

use Grimarina\Blog_Project\Blog\Repositories\UsersRepositories\UsersRepositoryInterface;
use Grimarina\Blog_Project\Blog\User;
use Grimarina\Blog_Project\Blog\UUID;
use Grimarina\Blog_Project\Exceptions\UserNotFoundException;
use Grimarina\Blog_Project\http\Actions\Users\FindByUsername;
use Grimarina\Blog_Project\http\ErrorResponse;
use Grimarina\Blog_Project\http\Request;
use Grimarina\Blog_Project\http\SuccessfulResponse;
use Grimarina\Blog_Project\UnitTests\DummyLogger;
use PHPUnit\Framework\TestCase;

class FindByUsernameActionTest extends TestCase 
{

    /**
    * @runInSeparateProcess
    * @preserveGlobalState disabled
    */

    public function testItReturnsErrorResponseIfNoUsernameProvided(): void
    {
        $request = new Request([], [], '');

        $usersRepository = $this->usersRepository([]);

        $action = new FindByUsername($usersRepository, new DummyLogger());

        $response = $action->handle($request);

        $this->assertInstanceOf(ErrorResponse::class, $response);

        $this->expectOutputString('{"success":false,"reason":"No such query param in the request: username"}');

        $response->send();
    }

    /**
    * @runInSeparateProcess
    * @preserveGlobalState disabled
    */

    public function testItReturnsErrorResponseIfUserNotFound(): void
    {
        $request = new Request(['username' => 'ivan'], [], '');

        $usersRepository = $this->usersRepository([]);

        $action = new FindByUsername($usersRepository, new DummyLogger());

        $response = $action->handle($request);

        $this->assertInstanceOf(ErrorResponse::class, $response);

        $this->expectOutputString('{"success":false,"reason":"Not found"}');

        $response->send();
    }

    /**
    * @runInSeparateProcess
    * @preserveGlobalState disabled
    */

    public function testItReturnsSuccessfulResponse(): void
    {
        $request = new Request(['username' => 'ivan'], [], '');

        $usersRepository = $this->usersRepository([
            new User(
                UUID::random(),
                'ivan',
                'Ivan',
                'Nikitin'
            ),
        ]);

        $action = new FindByUsername($usersRepository, new DummyLogger());

        $response = $action->handle($request);

        $this->assertInstanceOf(SuccessfulResponse::class, $response);

        $this->expectOutputString('{"success":true,"data":{"username":"ivan","firstname":"Ivan","lastname":"Nikitin"}}');

        $response->send();
    }

    private function usersRepository(array $users): UsersRepositoryInterface
    {
        return new class($users) implements UsersRepositoryInterface
        {
            public function __construct(
                private array $users
            )
            {
            }

            public function save(User $user): void
            {
            }

            public function get(UUID $uuid): User
            {
                throw new UserNotFoundException('Not found');
            }

            public function getByUsername(string $username): User
            {
                foreach ($this->users as $user) {
                    if ($user instanceof User && $username === $user->getUsername()) {
                        return $user;
                    }
                }

                throw new UserNotFoundException('Not found');
            }
        };
    }
}
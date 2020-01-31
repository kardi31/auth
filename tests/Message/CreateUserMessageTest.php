<?php
declare(strict_types=1);

namespace App\Test\Message;

use App\Entity\User;
use App\Message\CreateUserMessage;
use PHPUnit\Framework\TestCase;

class CreateUserMessageTest extends TestCase
{
    /**
     * @var CreateUserMessage
     */
    private $message;

    public function testCreateUserMessageReturnUser()
    {
        $user = $this->createMock(User::class);
        $password = 'plainpassword123';
        $this->message = new CreateUserMessage($user, $password);
        $this->assertInstanceOf(User::class, $this->message->getUser());
        $this->assertEquals($password, $this->message->getPassword());
    }
}

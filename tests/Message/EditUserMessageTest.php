<?php
declare(strict_types=1);

namespace App\Test\Message;

use App\Entity\User;
use App\Message\CreateUserMessage;
use App\Message\EditUserMessage;
use PHPUnit\Framework\TestCase;

class EditUserMessageTest extends TestCase
{
    /**
     * @var CreateUserMessage
     */
    private $message;

    public function testEditUserMessageReturnUserAndPassword()
    {
        $user = $this->createMock(User::class);
        $plainPassword = 'somePlainPassword';
        $this->message = new EditUserMessage($user, $plainPassword);
        $this->assertInstanceOf(User::class, $this->message->getUser());

        $this->assertEquals($plainPassword, $this->message->getNewPassword());
    }
}

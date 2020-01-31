<?php
declare(strict_types=1);

namespace App\Test\Message\Handler;

use App\Entity\User;
use App\Message\CreateUserMessage;
use App\Message\Handler\CreateUserMessageHandler;
use App\Repository\UserRepository;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class CreateUserMessageHandlerTest extends TestCase
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * @var CreateUserMessageHandler
     */
    private $handler;

    public function testPasswordIsEncoded()
    {
        $plainPassword = 'somePlainPassword';
        $user = $this->createMock(User::class);
        $this->userRepository = $this->createMock(UserRepository::class);
        $this->userRepository
            ->expects($this->once())
            ->method('save')
            ->with($user);
        $encodedPassword = 'someEncodedPassword';
        $this->passwordEncoder = $this->createMock(UserPasswordEncoderInterface::class);
        $this->passwordEncoder
            ->expects($this->once())
            ->method('encodePassword')
            ->with($user, $plainPassword)
            ->willReturn($encodedPassword);

        $user
            ->expects($this->once())
            ->method('setPassword')
            ->with($encodedPassword);

        $this->handler = new CreateUserMessageHandler($this->userRepository, $this->passwordEncoder);

        $message = $this->createMock(CreateUserMessage::class);
        $message
            ->expects($this->once())
            ->method('getUser')
            ->willReturn($user);
        $message
            ->expects($this->once())
            ->method('getPassword')
            ->willReturn($plainPassword);

        $result = ($this->handler)($message);
    }
}

<?php
declare(strict_types=1);

namespace App\Test\Message\Handler;

use App\Entity\User;
use App\Message\EditUserMessage;
use App\Message\Handler\EditUserMessageHandler;
use App\Repository\UserRepository;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class EditUserMessageHandlerTest extends TestCase
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * @var EditUserMessageHandler
     */
    private $handler;

    /**
     * @var UserRepository
     */
    private $userRepository;

    public function testNewPasswordIsEncodedWhenPassed()
    {
        $plainNewPassword = 'somePlainPassword';
        $user = $this->createMock(User::class);
        $encodedPassword = 'someEncodedPassword';

        $this->userRepository = $this->createMock(UserRepository::class);

        $this->passwordEncoder = $this->createMock(UserPasswordEncoderInterface::class);
        $this->passwordEncoder
            ->expects($this->once())
            ->method('encodePassword')
            ->with($user, $plainNewPassword)
            ->willReturn($encodedPassword);

        $user
            ->expects($this->once())
            ->method('setPassword')
            ->with($encodedPassword);

        $this->userRepository->expects($this->once())
            ->method('save')
            ->with($user);

        $this->handler = new EditUserMessageHandler($this->passwordEncoder, $this->userRepository);

        $message = $this->createMock(EditUserMessage::class);
        $message->expects($this->once())
            ->method('getUser')
            ->willReturn($user);
        $message->expects($this->once())
            ->method('getNewPassword')
            ->willReturn($plainNewPassword);

        $result = ($this->handler)($message);
    }

    public function testPasswordIsNotUpdatedWhenNewPasswordNotPassed()
    {
        $user = $this->createMock(User::class);
        $this->passwordEncoder = $this->createMock(UserPasswordEncoderInterface::class);
        $this->passwordEncoder
            ->expects($this->never())
            ->method('encodePassword');

        $user
            ->expects($this->never())
            ->method('setPassword');

        $this->userRepository = $this->createMock(UserRepository::class);
        $this->userRepository->expects($this->once())
            ->method('save')
            ->with($user);

        $this->handler = new EditUserMessageHandler($this->passwordEncoder, $this->userRepository);

        $message = $this->createMock(EditUserMessage::class);
        $message->expects($this->once())
            ->method('getUser')
            ->willReturn($user);
        $message->expects($this->once())
            ->method('getNewPassword')
            ->willReturn(null);

        $result = ($this->handler)($message);
    }
}

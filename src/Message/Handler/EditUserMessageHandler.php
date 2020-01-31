<?php
declare(strict_types=1);

namespace App\Message\Handler;

use App\Message\EditUserMessage;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class EditUserMessageHandler
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder, UserRepository $userRepository)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->userRepository = $userRepository;
    }

    public function __invoke(EditUserMessage $message)
    {
        $user = $message->getUser();
        $newPassword = $message->getNewPassword();

        /** If new password is passed, encode and update the password, otherwise leave the previous one unchanged */
        if ($newPassword !== null) {
            $user->setPassword(
                $this->passwordEncoder->encodePassword(
                    $user,
                    $newPassword
                )
            );
        }

        $this->userRepository->save($user);
    }
}

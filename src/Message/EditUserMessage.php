<?php
declare(strict_types=1);

namespace App\Message;

use App\Entity\User;

class EditUserMessage
{
    /**
     * @var User
     */
    private $user;

    /**
     * @var string|null
     */
    private $newPassword;

    public function __construct(User $user, ?string $newPassword)
    {
        $this->user = $user;
        $this->newPassword = $newPassword;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getNewPassword(): ?string
    {
        return $this->newPassword;
    }
}

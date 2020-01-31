<?php
declare(strict_types=1);

namespace App\Message;

use App\Entity\User;

class CreateUserMessage
{
    /**
     * @var User
     */
    private $user;

    /**
     * @var string
     */
    private $password;

    public function __construct(User $user, string $password)
    {
        $this->user = $user;
        $this->password = $password;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}

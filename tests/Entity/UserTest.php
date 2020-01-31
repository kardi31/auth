<?php
declare(strict_types=1);

namespace App\Test\Entity;

use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    /**
     * @var User
     */
    private $user;

    public function testUserJsonSerialize()
    {
        $email = 'testemail@test.pl';
        $roles = [
            'ROLE_USER',
            'ROLE_ADMIN_USER',
        ];
        $this->user = new User();
        $this->user->setEmail($email)
            ->setRoles($roles);

        $expectedResult = [
            'email' => $email,
            'roles' => $roles,
            'username' => $email,
        ];
        $this->assertEquals($expectedResult, $this->user->jsonSerialize());
    }
}

<?php
declare(strict_types=1);

namespace App\Model;

trait UserSerializeTrait
{
    public function jsonSerialize()
    {
        return [
            'email' => $this->getEmail(),
            'username' => $this->getUsername(),
            'roles' => $this->getRoles(),
        ];
    }
}

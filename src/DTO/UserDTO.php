<?php
namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class UserDTO {
    #[Assert\NotBlank]
    #[Assert\Email]
    public string $email;

    #[Assert\NotBlank]
    #[Assert\Length(min: 6)]
    public string $password;

    /**
     * @var array<string>
     */
    #[Assert\Choice(choices: ['ROLE_ADMIN', 'ROLE_USER'], multiple: true)]
    public array $roles = ['ROLE_USER'];
}
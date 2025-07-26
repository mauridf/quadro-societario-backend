<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class SocioDTO {
    #[Assert\NotBlank]
    public string $nome;

    #[Assert\NotBlank]
    #[Assert\Regex(pattern: '/^\d{11}$/')]
    public string $cpf;

    #[Assert\NotBlank]
    #[Assert\Range(min: 0.01, max: 100)]
    public float $percentualParticipacao;
}
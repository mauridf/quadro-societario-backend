<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\Constraints\Cpf;

class SocioDTO {
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    public string $nome;

    #[Assert\NotBlank]
    #[Assert\Regex(pattern: '/^\d{11}$/')]
    #[Cpf]
    public string $cpf;

    #[Assert\NotBlank]
    #[Assert\Range(min: 0.1, max: 100)]
    public float $percentualParticipacao;

    #[Assert\NotNull]
    public int $empresaId;
}
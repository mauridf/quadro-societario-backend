<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class SocioDTO {
    #[Assert\NotBlank(message: "O nome é obrigatório", groups: ["create"])]
    public ?string $nome = null;

    #[Assert\NotBlank(message: "CPF é obrigatório", groups: ["create"])]
    #[Assert\Regex(
        pattern: '/^\d{11}$/',
        message: "CPF deve conter 11 dígitos",
        groups: ["create", "update"]
    )]
    public ?string $cpf = null;

    #[Assert\NotBlank(message: "Percentual é obrigatório", groups: ["create"])]
    #[Assert\Range(
        min: 0.01,
        max: 100,
        notInRangeMessage: "O percentual deve estar entre {{ min }}% e {{ max }}%",
        groups: ["create", "update"]
    )]
    public ?float $percentualParticipacao = null;
    
    #[Assert\NotNull(message: "Empresa é obrigatória", groups: ["create"])]
    public ?int $empresaId = null;
}
<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\Constraints\Cnpj;

class EmpresaDTO {
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    public string $nome;

    #[Assert\NotBlank]
    #[Assert\Regex(pattern: '/^\d{14}$/')]
    #[Cnpj]
    public string $cnpj;

    #[Assert\Type(\DateTimeInterface::class)]
    public ?\DateTime $dataFundacao = null;
    
    /** @var SocioDTO[] */
    public array $socios = [];
}
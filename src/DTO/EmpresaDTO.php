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

    #[Assert\Type('string')]
    #[Assert\Regex(
        pattern: '/^\d{4}-\d{2}-\d{2}$/',
        message: 'A data de fundação deve estar no formato YYYY-MM-DD'
    )]
    public ?string $dataFundacao = null;
    
    /** @var SocioDTO[] */
    public array $socios = [];

    // Método para converter a string em DateTime quando necessário
    public function getDataFundacaoDateTime(): ?\DateTimeInterface
    {
        if ($this->dataFundacao === null || $this->dataFundacao === '') {
            return null;
        }
        
        try {
            return new \DateTime($this->dataFundacao);
        } catch (\Exception $e) {
            return null;
        }
    }
}
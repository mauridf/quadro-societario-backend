<?php

namespace App\Entity;

use App\Repository\EmpresaRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;

#[ORM\Entity(repositoryClass: EmpresaRepository::class)]
#[ApiResource]
class Empresa
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nome = null;

    #[ORM\Column(length: 14)]
    private ?string $cnpj = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTime $dataFundacao = null;

    #[ORM\OneToMany(targetEntity: Socio::class, mappedBy: 'empresa', cascade: ['persist', 'remove'])]
    private Collection $socios;

    public function __construct() {
        $this->socios = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNome(): ?string
    {
        return $this->nome;
    }

    public function setNome(string $nome): static
    {
        $this->nome = $nome;

        return $this;
    }

    public function getCnpj(): ?string
    {
        return $this->cnpj;
    }

    public function setCnpj(string $cnpj): static
    {
        $this->cnpj = $cnpj;

        return $this;
    }

    public function getDataFundacao(): ?\DateTime
    {
        return $this->dataFundacao;
    }

    public function setDataFundacao(?\DateTime $dataFundacao): static
    {
        $this->dataFundacao = $dataFundacao;

        return $this;
    }
}

<?php

namespace App\Entity;

use App\Repository\SocioRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: SocioRepository::class)]
#[ApiResource]
class Socio
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['socio:read', 'empresa:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['socio:read', 'socio:write', 'empresa:read'])]
    private ?string $nome = null;

    #[ORM\Column(length: 11)]
    #[Groups(['socio:read', 'empresa:read'])]
    private ?string $cpf = null;

    #[ORM\Column]
    #[Groups(['socio:read', 'socio:write', 'empresa:read'])]
    private ?float $percentualParticipacao = null;

    #[ORM\ManyToOne(targetEntity: Empresa::class, inversedBy: 'socios')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['socio:read'])] // NÃO incluir empresa:read aqui para evitar recursão infinita
    private ?Empresa $empresa = null;

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

    public function getCpf(): ?string
    {
        return $this->cpf;
    }

    public function setCpf(string $cpf): static
    {
        $this->cpf = $cpf;
        return $this;
    }

    public function getPercentualParticipacao(): ?float
    {
        return $this->percentualParticipacao;
    }

    public function setPercentualParticipacao(float $percentualParticipacao): static
    {
        $this->percentualParticipacao = $percentualParticipacao;
        return $this;
    }

    public function getEmpresa(): ?Empresa
    {
        return $this->empresa;
    }

    public function setEmpresa(?Empresa $empresa): static
    {
        $this->empresa = $empresa;
        return $this;
    }
}

<?php

namespace App\Entity;

use App\Repository\EmpresaRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: EmpresaRepository::class)]
#[ApiResource]
class Empresa
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['empresa:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['empresa:read', 'empresa:write'])]
    private ?string $nome = null;

    #[ORM\Column(length: 14)]
    #[Groups(['empresa:read'])]
    private ?string $cnpj = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    #[Groups(['empresa:read'])]
    private ?\DateTimeInterface $dataFundacao = null;

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

    public function getDataFundacao(): ?string
    {
        return $this->dataFundacao?->format('Y-m-d');
    }

    public function setDataFundacao(?\DateTime $dataFundacao): static
    {
        $this->dataFundacao = $dataFundacao;

        return $this;
    }

    public function getSocios(): Collection
    {
        return $this->socios;
    }

    public function addSocio(Socio $socio): static
    {
        if (!$this->socios->contains($socio)) {
            $this->socios->add($socio);
            $socio->setEmpresa($this);
        }

        return $this;
    }

    public function removeSocio(Socio $socio): static
    {
        if ($this->socios->removeElement($socio)) {
            // set the owning side to null (unless already changed)
            if ($socio->getEmpresa() === $this) {
                $socio->setEmpresa(null);
            }
        }

        return $this;
    }
}

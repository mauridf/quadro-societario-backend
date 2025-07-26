<?php

namespace App\Service;

use App\Entity\Socio;
use App\DTO\SocioDTO;
use App\Repository\EmpresaRepository;
use Doctrine\ORM\EntityManagerInterface;

class SocioService {
    public function __construct(
        private EntityManagerInterface $em,
        private EmpresaRepository $empresaRepo
    ) {}

    public function criarSocio(SocioDTO $dto): Socio {
        $empresa = $this->empresaRepo->find($dto->empresaId);
        if (!$empresa) {
            throw new \InvalidArgumentException('Empresa não encontrada!');
        }

        $socio = new Socio();
        $socio->setNome($dto->nome);
        $socio->setCpf($dto->cpf);
        $socio->setPercentualParticipacao($dto->percentualParticipacao);
        $socio->setEmpresa($empresa);

        $this->em->persist($socio);
        $this->em->flush();

        return $socio;
    }

    public function listarPorEmpresa(int $empresaId): array {
        return $this->em->getRepository(Socio::class)->findBy(['empresa' => $empresaId]);
    }

    public function buscarPorId(int $socioId, int $empresaId): ?Socio {
        $socio = $this->em->getRepository(Socio::class)->find($socioId);
        if ($socio) {
            $this->verificarEmpresa($socio, $empresaId);
        }
        return $socio;
    }

    public function atualizarSocio(int $socioId, SocioDTO $dto): Socio {
        $socio = $this->buscarPorId($socioId, $dto->empresaId);
        if (!$socio) {
            throw new \InvalidArgumentException('Sócio não encontrado!');
        }

        if ($dto->nome !== null) {
            $socio->setNome($dto->nome);
        }
        
        if ($dto->cpf !== null) {
            $socio->setCpf($dto->cpf);
        }
        
        if ($dto->percentualParticipacao !== null) {
            $socio->setPercentualParticipacao($dto->percentualParticipacao);
        }

        $this->em->flush();
        return $socio;
    }

    public function removerSocio(int $socioId, int $empresaId): void {
        $socio = $this->buscarPorId($socioId, $empresaId);
        if (!$socio) {
            throw new \InvalidArgumentException('Sócio não encontrado!');
        }

        $this->em->remove($socio);
        $this->em->flush();
    }

    private function verificarEmpresa(Socio $socio, int $empresaId): void {
        if ($socio->getEmpresa()->getId() !== $empresaId) {
            throw new \InvalidArgumentException('Sócio não pertence a esta empresa!');
        }
    }
}
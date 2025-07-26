<?php

namespace App\Service;

use App\Entity\Empresa;
use App\DTO\EmpresaDTO;
use Doctrine\ORM\EntityManagerInterface;

class EmpresaService {
    public function __construct(private EntityManagerInterface $em) {}

    public function criarEmpresa(EmpresaDTO $dto): Empresa {
        $empresa = new Empresa();
        $empresa->setNome($dto->nome);
        $empresa->setCnpj($dto->cnpj);
        $empresa->setDataFundacao($dto->dataFundacao);

        $this->em->persist($empresa);
        $this->em->flush();

        return $empresa;
    }

    public function listarTodos(): array {
        return $this->em->getRepository(Empresa::class)->findAll();
    }

    public function buscarPorId(int $id): ?Empresa {
        return $this->em->getRepository(Empresa::class)->find($id);
    }

    public function atualizarEmpresa(int $id, EmpresaDTO $dto): Empresa {
        $empresa = $this->buscarPorId($id);
        // ... (atualiza propriedades)
        $this->em->flush();
        return $empresa;
    }

    public function removerEmpresa(int $id): void {
        $empresa = $this->buscarPorId($id);
        $this->em->remove($empresa);
        $this->em->flush();
    }
}
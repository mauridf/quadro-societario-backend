<?php

namespace App\Service;

use App\Entity\Empresa;
use App\DTO\EmpresaDTO;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;

class EmpresaService {
    public function __construct(private EntityManagerInterface $em) {}

    public function criarEmpresa(EmpresaDTO $dto): Empresa
    {
        $empresa = new Empresa();
        $empresa->setNome($dto->nome);
        $empresa->setCnpj($dto->cnpj);
        
        // Use o método que converte string para DateTime
        if ($dto->dataFundacao !== null) {
            $empresa->setDataFundacao(new \DateTime($dto->dataFundacao));
        }

        $this->em->persist($empresa);
        $this->em->flush();

        return $empresa;
    }

    public function listarComPaginacao(int $page = 1, int $pageSize = 10, ?string $search = null): array
    {
        $queryBuilder = $this->em->getRepository(Empresa::class)
            ->createQueryBuilder('e');
        
        if ($search) {
            $queryBuilder->where('e.nome LIKE :search')
                        ->setParameter('search', '%'.$search.'%');
        }
        
        $query = $queryBuilder->getQuery();
        
        $paginator = new Paginator($query);
        $paginator->getQuery()
            ->setFirstResult($pageSize * ($page - 1))
            ->setMaxResults($pageSize);
        
        // Convertemos para array para garantir a serialização correta
        $data = [];
        foreach ($paginator as $empresa) {
            $data[] = $empresa;
        }
        
        return [
            'data' => $data, // Mantendo consistência com o nome usado no frontend
            'total' => count($paginator)
        ];
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
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

        // Verifica se existem sócios no DTO
        if (!empty($dto->socios)) {
            foreach ($dto->socios as $socioDto) {
                $socio = new Socio();
                $socio->setNome($socioDto->nome);
                $socio->setCpf($socioDto->cpf);
                $socio->setPercentualParticipacao($socioDto->percentualParticipacao);
                $empresa->addSocio($socio);
            }
        }

        $this->em->persist($empresa);
        $this->em->flush();

        return $empresa;
    }

    public function listarComPaginacao(int $page, int $pageSize, ?string $search = null): array {
        $queryBuilder = $this->em->getRepository(Empresa::class)->createQueryBuilder('e');
        
        if ($search) {
            $queryBuilder->where('e.nome LIKE :search')
                        ->setParameter('search', '%'.$search.'%');
        }
        
        $query = $queryBuilder->getQuery();
        
        // Paginação
        $paginator = new \Doctrine\ORM\Tools\Pagination\Paginator($query);
        $paginator->getQuery()
            ->setFirstResult($pageSize * ($page - 1))
            ->setMaxResults($pageSize);
        
        return [
            'items' => $paginator->getIterator(),
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
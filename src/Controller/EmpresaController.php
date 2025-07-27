<?php

namespace App\Controller;

use App\DTO\EmpresaDTO;
use App\Service\EmpresaService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/empresas')]
class EmpresaController extends AbstractController {
    #[Route('', methods: ['POST'])]
    public function create(
        Request $request,
        EmpresaService $service,
        ValidatorInterface $validator
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);
        
        $dto = new EmpresaDTO();
        $dto->nome = $data['nome'] ?? null;
        $dto->cnpj = $data['cnpj'] ?? null;
        $dto->dataFundacao = isset($data['dataFundacao']) ? new \DateTime($data['dataFundacao']) : null;

        $errors = $validator->validate($dto);
        if (count($errors) > 0) {
            return $this->json(['errors' => (string) $errors], 400);
        }

        $empresa = $service->criarEmpresa($dto);
        return $this->json($empresa, 201);
    }

    #[Route('', methods: ['GET'])]
    public function listAll(Request $request, EmpresaService $service): JsonResponse {
        $page = $request->query->getInt('page', 1);
        $pageSize = $request->query->getInt('pageSize', 10);
        $search = $request->query->get('search', null);

        $result = $service->listarComPaginacao($page, $pageSize, $search);
        
        return $this->json([
            'data' => $result['items'],
            'total' => $result['total']
        ], 200, [], ['groups' => 'empresa:read']);
    }

    #[Route('/{id}', methods: ['GET'])]
    public function show(int $id, EmpresaService $service): JsonResponse {
        $empresa = $service->buscarPorId($id);
        return $this->json($empresa, 200, [], ['groups' => 'empresa:read']);
    }

    #[Route('/{id}', methods: ['PUT'])]
    public function update(
        int $id,
        Request $request,
        EmpresaService $service,
        ValidatorInterface $validator
    ): JsonResponse {
        $dto = new EmpresaDTO();
        // ... (mesma lógica do create, mas com $id)
        $empresa = $service->atualizarEmpresa($id, $dto);
        return $this->json($empresa);
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(int $id, EmpresaService $service): JsonResponse {
        $service->removerEmpresa($id);
        return $this->json(null, 204);
    }
}
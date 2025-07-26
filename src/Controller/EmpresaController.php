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
        $dto = new EmpresaDTO();
        $dto->nome = $request->get('nome');
        $dto->cnpj = $request->get('cnpj');
        $dto->dataFundacao = $request->get('dataFundacao') ? new \DateTime($request->get('dataFundacao')) : null;

        $errors = $validator->validate($dto);
        if (count($errors) > 0) {
            return $this->json(['errors' => (string) $errors], 400);
        }

        $empresa = $service->criarEmpresa($dto);
        return $this->json($empresa, 201);
    }

    #[Route('', methods: ['GET'])]
    public function listAll(EmpresaService $service): JsonResponse {
        $empresas = $service->listarTodos();
        return $this->json($empresas);
    }

    #[Route('/{id}', methods: ['GET'])]
    public function show(int $id, EmpresaService $service): JsonResponse {
        $empresa = $service->buscarPorId($id);
        if (!$empresa) {
            return $this->json(['error' => 'Empresa não encontrada!'], 404);
        }
        return $this->json($empresa);
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
<?php

namespace App\Controller;

use App\DTO\SocioDTO;
use App\Service\SocioService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/empresas/{empresaId}/socios')]
class SocioController extends AbstractController {
    #[Route('', methods: ['POST'])]
    public function create(
        int $empresaId,
        Request $request,
        SocioService $service,
        ValidatorInterface $validator
    ): JsonResponse {
        $dto = new SocioDTO();
        $dto->nome = $request->get('nome');
        $dto->cpf = $request->get('cpf');
        $dto->percentualParticipacao = (float) $request->get('percentualParticipacao');
        $dto->empresaId = $empresaId;

        $errors = $validator->validate($dto);
        if (count($errors) > 0) {
            return $this->json(['errors' => (string) $errors], 400);
        }

        $socio = $service->criarSocio($dto);
        return $this->json($socio, 201);
    }

    #[Route('', methods: ['GET'])]
    public function list(int $empresaId, SocioService $service): JsonResponse {
        $socios = $service->listarPorEmpresa($empresaId);
        return $this->json($socios);
    }

    #[Route('/{socioId}', methods: ['GET'])]
    public function show(int $empresaId, int $socioId, SocioService $service): JsonResponse {
        $socio = $service->buscarPorId($socioId, $empresaId);
        if (!$socio) {
            return $this->json(['error' => 'Sócio não encontrado!'], 404);
        }
        return $this->json($socio);
    }

    #[Route('/{socioId}', methods: ['PUT'])]
    public function update(
        int $empresaId,
        int $socioId,
        Request $request,
        SocioService $service,
        ValidatorInterface $validator
    ): JsonResponse {
        $dto = new SocioDTO();
        $dto->nome = $request->get('nome');
        $dto->cpf = $request->get('cpf');
        $dto->percentualParticipacao = (float) $request->get('percentualParticipacao');
        $dto->empresaId = $empresaId;

        $errors = $validator->validate($dto);
        if (count($errors) > 0) {
            return $this->json(['errors' => (string) $errors], 400);
        }

        $socio = $service->atualizarSocio($socioId, $dto);
        return $this->json($socio);
    }

    #[Route('/{socioId}', methods: ['DELETE'])]
    public function delete(int $empresaId, int $socioId, SocioService $service): JsonResponse {
        $service->removerSocio($socioId, $empresaId);
        return $this->json(null, 204);
    }
}
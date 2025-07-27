<?php

namespace App\Controller;

use App\DTO\SocioDTO;
use App\Service\SocioService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api')]
class SocioController extends AbstractController {
    #[Route('/empresas/{empresaId}/socios', methods: ['POST'])]
    public function create(
        int $empresaId,
        Request $request,
        SocioService $service,
        ValidatorInterface $validator
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);
        
        $dto = new SocioDTO();
        $dto->nome = $data['nome'] ?? null;
        $dto->cpf = $data['cpf'] ?? null;
        $dto->percentualParticipacao = (float) ($data['percentualParticipacao'] ?? 0);
        $dto->empresaId = $empresaId;

        $errors = $validator->validate($dto);
        if (count($errors) > 0) {
        $errorMessages = [];
        foreach ($errors as $error) {
            $errorMessages[] = $error->getPropertyPath() . ': ' . $error->getMessage();
        }
        return $this->json(['errors' => $errorMessages], 400);
    }

        $socio = $service->criarSocio($dto);
        // return $this->json($socio, 201);
        return $this->json($socio, 201, [], ['groups' => 'socio:read']);
    }

    #[Route('/empresas/{empresaId}/socios', methods: ['GET'])]
    public function list(
        int $empresaId, 
        Request $request,
        SocioService $service,
        SerializerInterface $serializer
    ): JsonResponse {
        $page = $request->query->getInt('page', 1);
        $pageSize = $request->query->getInt('pageSize', 10);
        $search = $request->query->get('search', null);

        $result = $service->listarPorEmpresaComPaginacao($empresaId, $page, $pageSize, $search);
        
        $data = $serializer->normalize($result['data'], null, [
            'groups' => 'socio:read'
        ]);
        
        return $this->json([
            'data' => $data,
            'total' => $result['total']
        ]);
    }

    #[Route('/socios', name: 'socios_list_all', methods: ['GET'])]
    public function listAllSocios(
        Request $request, 
        SocioService $service,
        SerializerInterface $serializer
    ): JsonResponse {
        $page = $request->query->getInt('page', 1);
        $pageSize = $request->query->getInt('pageSize', 10);
        $search = $request->query->get('search', null);

        $result = $service->listarTodosSocios($page, $pageSize, $search);
        
        $data = $serializer->normalize($result['data'], null, [
            'groups' => ['socio:read', 'socio:details', 'empresa:simple'] 
        ]);
        
        return $this->json([
            'data' => $data,
            'total' => $result['total']
        ]);
    }

    #[Route('/empresas/{empresaId}/socios/{socioId}', methods: ['GET'])]
    public function show(int $empresaId, int $socioId, SocioService $service): JsonResponse {
        $socio = $service->buscarPorId($socioId, $empresaId);
        if (!$socio) {
            return $this->json(['error' => 'Sócio não encontrado!'], 404);
        }
        return $this->json($socio, 200, [], ['groups' => 'socio:read']);
    }

    #[Route('/empresas/{empresaId}/socios/{socioId}', methods: ['PUT'])]
    public function update(
        int $empresaId,
        int $socioId,
        Request $request,
        SocioService $service,
        ValidatorInterface $validator
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);
        
        $dto = new SocioDTO();
        $dto->nome = $data['nome'] ?? null;
        $dto->cpf = $data['cpf'] ?? null;
        $dto->percentualParticipacao = isset($data['percentualParticipacao']) 
            ? (float) $data['percentualParticipacao'] 
            : null;
        $dto->empresaId = $empresaId;

        $errors = $validator->validate($dto, null, ['update']);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getPropertyPath() . ': ' . $error->getMessage();
            }
            return $this->json(['errors' => $errorMessages], 400);
        }

        $socio = $service->atualizarSocio($socioId, $dto);
        return $this->json($socio, 200, [], ['groups' => 'socio:read']);
    }

    #[Route('/empresas/{empresaId}/socios/{socioId}', methods: ['DELETE'])]
    public function delete(int $empresaId, int $socioId, SocioService $service): JsonResponse {
        $service->removerSocio($socioId, $empresaId);
        return $this->json(null, 204);
    }
}
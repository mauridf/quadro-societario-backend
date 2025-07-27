<?php

namespace App\Controller;

use App\DTO\EmpresaDTO;
use App\Service\EmpresaService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/empresas')]
class EmpresaController extends AbstractController {
    private function createEmpresaDTOFromRequest(Request $request, ValidatorInterface $validator): EmpresaDTO
    {
        $data = json_decode($request->getContent(), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new BadRequestHttpException('JSON malformado: ' . json_last_error_msg());
        }

        $dto = new EmpresaDTO();

        $dto->nome = $data['nome'] ?? null;
        $dto->cnpj = $data['cnpj'] ?? null;

        if (isset($data['dataFundacao']) && !empty($data['dataFundacao'])) {
            try {
                $dto->dataFundacao = new \DateTime($data['dataFundacao']);
            } catch (\Exception $e) {
                throw new BadRequestHttpException('Data de fundação inválida. Use formato YYYY-MM-DD.');
            }
        } else {
            $dto->dataFundacao = null;
        }

        $dto->socios = $data['socios'] ?? null;

        $errors = $validator->validate($dto);
        if (count($errors) > 0) {
            $messages = [];
            foreach ($errors as $error) {
                $messages[] = $error->getPropertyPath() . ': ' . $error->getMessage();
            }
            throw new BadRequestHttpException('Erro de validação: ' . implode('; ', $messages));
        }

        return $dto;
    }

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
        $dto->dataFundacao = $data['dataFundacao'] ?? null; // Mantém como string

        $errors = $validator->validate($dto);
        if (count($errors) > 0) {
            return $this->json(['errors' => (string) $errors], 400);
        }

        try {
            $empresa = $service->criarEmpresa($dto);
            return $this->json($empresa, 201, [], ['groups' => 'empresa:read']);
        } catch (\Exception $e) {
            return $this->json([
                'error' => 'Erro ao criar empresa',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    #[Route('', methods: ['GET'])]
    public function listAll(
        Request $request, 
        EmpresaService $service,
        SerializerInterface $serializer
    ): JsonResponse
    {
        $page = $request->query->getInt('page', 1);
        $pageSize = $request->query->getInt('pageSize', 10);
        $search = $request->query->get('search', null);

        $result = $service->listarComPaginacao($page, $pageSize, $search);
        
        $data = $serializer->normalize($result['data'], null, [
            'groups' => 'empresa:read'
        ]);
        
        return $this->json([
            'data' => $data,
            'total' => $result['total']
        ]);
    }

    #[Route('', methods: ['PUT'])]
    public function update(int $id, Request $request, EmpresaService $service, ValidatorInterface $validator): JsonResponse
    {
        try {
            $dto = $this->createEmpresaDTOFromRequest($request, $validator);
            $empresa = $service->atualizarEmpresa($id, $dto);
            return $this->json($empresa, 200, [], ['groups' => 'empresa:read']);
        } catch (BadRequestHttpException $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        } catch (\Throwable $e) {
            return $this->json([
                'error' => 'Erro interno do servidor.',
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ], 500);
        }
    }


    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(int $id, EmpresaService $service): JsonResponse {
        $service->removerEmpresa($id);
        return $this->json(null, 204);
    }
}
<?php

namespace App\Controller;

use App\DTO\UserDTO;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/users')]
class UserController extends AbstractController {
    public function __construct(
        private UserService $service,
        private ValidatorInterface $validator
    ) {}

    #[Route('', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
    
        $dto = new UserDTO();
        $dto->email = $data['email'] ?? null;
        $dto->password = $data['password'] ?? null;
        $dto->roles = $data['roles'] ?? ['ROLE_USER'];

        $errors = $this->validator->validate($dto);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getPropertyPath() . ': ' . $error->getMessage();
            }
            return $this->json(['errors' => $errorMessages], 400);
        }

        $user = $this->service->criarUser($dto);
        return $this->json($user, 201);
    }

    #[Route('', methods: ['GET'])]
    public function listAll(SerializerInterface $serializer): JsonResponse 
    {
        $users = $this->service->listarTodos();
        
        return $this->json($users, 200, [], [
            'groups' => ['user:read']
        ]);
    }

    #[Route('/{id}', methods: ['GET'])]
    public function show(int $id): JsonResponse {
        $user = $this->service->buscarPorId($id);
        if (!$user) {
            return $this->json(['error' => 'Usuário não encontrado!'], 404);
        }
        return $this->json($user);
    }

    #[Route('/{id}', methods: ['PUT'])]
    public function update(
        int $id,
        Request $request,
        ValidatorInterface $validator
    ): JsonResponse {
        // Decodifica o conteúdo JSON da requisição
        $data = json_decode($request->getContent(), true);
        
        $dto = new UserDTO();
        $dto->email = $data['email'] ?? null;
        $dto->password = $data['password'] ?? null;
        $dto->roles = $data['roles'] ?? ['ROLE_USER'];

        $errors = $this->validator->validate($dto);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getPropertyPath() . ': ' . $error->getMessage();
            }
            return $this->json(['errors' => $errorMessages], 400);
        }

        $user = $this->service->atualizarUser($id, $dto);
        return $this->json($user);
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse {
        $this->service->removerUser($id);
        return $this->json(null, 204);
    }
}
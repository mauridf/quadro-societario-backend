<?php

namespace App\Controller;

use App\DTO\UserDTO;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/users')]
class UserController extends AbstractController {
    public function __construct(private UserService $service) {}

    #[Route('', methods: ['POST'])]
    public function create(
        Request $request,
        ValidatorInterface $validator
    ): JsonResponse {
        $dto = new UserDTO();
        $dto->email = $request->get('email');
        $dto->password = $request->get('password');
        $dto->roles = $request->get('roles', ['ROLE_USER']);

        $errors = $validator->validate($dto);
        if (count($errors) > 0) {
            return $this->json(['errors' => (string) $errors], 400);
        }

        $user = $this->service->criarUser($dto);
        return $this->json($user, 201);
    }

    #[Route('', methods: ['GET'])]
    public function listAll(): JsonResponse {
        $users = $this->service->listarTodos();
        return $this->json($users);
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
        $dto = new UserDTO();
        $dto->email = $request->get('email');
        $dto->password = $request->get('password');
        $dto->roles = $request->get('roles', ['ROLE_USER']);

        $errors = $validator->validate($dto);
        if (count($errors) > 0) {
            return $this->json(['errors' => (string) $errors], 400);
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
<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/api/auth')]
class AuthController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
        private UserPasswordHasherInterface $passwordHasher,
        private JWTTokenManagerInterface $jwtManager
    ) {}

    #[Route('/login', methods: ['POST', 'OPTIONS'])]
    public function login(Request $request): JsonResponse
    {
        // Resposta para requisições OPTIONS (preflight)
        if ($request->getMethod() === 'OPTIONS') {
            return new JsonResponse([], 204, [
                'Access-Control-Allow-Origin' => 'http://localhost:4200',
                'Access-Control-Allow-Credentials' => 'true',
                'Access-Control-Allow-Methods' => 'POST, OPTIONS',
                'Access-Control-Allow-Headers' => 'Content-Type, Authorization'
            ]);
        }

        $data = json_decode($request->getContent(), true);
        
        $email = $data['email'] ?? '';
        $password = $data['password'] ?? '';

        $user = $this->em->getRepository(User::class)->findOneBy(['email' => $email]);

        if (!$user || !$this->passwordHasher->isPasswordValid($user, $password)) {
            throw new BadCredentialsException('Credenciais inválidas');
        }

        $token = $this->jwtManager->create($user);

        return $this->json([
            'token' => $token,
            'user' => [
                'id' => $user->getId(),
                'email' => $user->getEmail(),
                'roles' => $user->getRoles()
            ]
        ]);
    }

    #[Route('/me', methods: ['GET'])]
    public function me(): JsonResponse
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->json(['error' => 'Não autenticado'], 401);
        }

        return $this->json([
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'roles' => $user->getRoles()
        ]);
    }
}
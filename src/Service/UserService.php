<?php

namespace App\Service;

use App\Entity\User;
use App\DTO\UserDTO;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserService {
    public function __construct(
        private EntityManagerInterface $em,
        private UserPasswordHasherInterface $passwordHasher
    ) {}

    public function criarUser(UserDTO $dto): User {
        $user = new User();
        $user->setEmail($dto->email);
        $user->setPassword($this->passwordHasher->hashPassword($user, $dto->password));
        $user->setRoles($dto->roles);

        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }

    public function listarTodos(): array 
    {
        return $this->em->getRepository(User::class)->findAll();
    }

    public function buscarPorId(int $id): ?User {
        return $this->em->getRepository(User::class)->find($id);
    }

    public function atualizarUser(int $id, UserDTO $dto): User {
        $user = $this->buscarPorId($id);
        if (!$user) {
            throw new \InvalidArgumentException('Usuário não encontrado!');
        }

        if ($dto->email !== null) {
            $user->setEmail($dto->email);
        }
        
        if ($dto->password !== null) {
            $user->setPassword($this->passwordHasher->hashPassword($user, $dto->password));
        }
        
        if (!empty($dto->roles)) {
            $user->setRoles($dto->roles);
        }

        $this->em->flush();
        return $user;
    }

    public function removerUser(int $id): void {
        $user = $this->buscarPorId($id);
        if (!$user) {
            throw new \InvalidArgumentException('Usuário não encontrado!');
        }

        $this->em->remove($user);
        $this->em->flush();
    }
}
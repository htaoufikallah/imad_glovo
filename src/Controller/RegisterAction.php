<?php

namespace App\Controller;

use App\ApiResource\Register;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegisterAction extends AbstractController
{
    public function __construct(
        private readonly UserRepository  $userRepository,
        private readonly ManagerRegistry $managerRegistry,
        private readonly UserPasswordHasherInterface $userPasswordHasher,
    ) {
    }

    public function __invoke(Register $data): JsonResponse
    {
        $userEmail = $this->userRepository->findOneBy(['email' => $data->email]);

        if (null !== $userEmail) {
            throw new BadRequestHttpException('A user with this email already exists');
        }
        if ($data->rePassword !== $data->password) {
            throw new BadRequestHttpException('Password mismatch');
        }
        try {
            $user = new User();
            $user->setEmail($data->email);
            $user->setPassword($this->userPasswordHasher->hashPassword($user, $data->password));
            $user->setRoles(['ROLE_User']);
            $this->managerRegistry->getManager()->persist($user);
            $this->managerRegistry->getManager()->flush();

            return new JsonResponse(['message' => 'Registered successfully'], Response::HTTP_CREATED);
        }
        catch (\Throwable $throwable) {
            return new JsonResponse(['message' => $throwable->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}

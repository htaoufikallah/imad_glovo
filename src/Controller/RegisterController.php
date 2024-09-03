<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RegisterController extends AbstractController
{
    private $entityManager;
    private $passwordHasher;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher)
    {
        $this->entityManager = $entityManager;
        $this->passwordHasher = $passwordHasher;
    }

    #[Route('/register', name: 'app_register_get', methods: ['GET'])]
    public function showRegisterForm(): Response
    {
        // Rendu du formulaire d'enregistrement
        return $this->render('register/register.html.twig');
    }

    #[Route('/register', name: 'app_register_post', methods: ['POST'])]
    public function register(Request $request, ValidatorInterface $validator): Response
    {
        $username = $request->request->get('username');
        $email = $request->request->get('email');
        $password = $request->request->get('password');
        $confirmPassword = $request->request->get('confirm_password');
        $role = $request->request->get('role');

        // Vérification des champs requis
        if (empty($username) || empty($email) || empty($password) || empty($confirmPassword) || empty($role)) {
            $this->addFlash('error', 'Missing required fields');
            return $this->redirectToRoute('app_register_get');
        }

        // Vérification des mots de passe
        if ($password !== $confirmPassword) {
            $this->addFlash('error', 'Passwords do not match');
            return $this->redirectToRoute('app_register_get');
        }

        $user = new User();
        $user->setEmail($email);
        $user->setUsername($username);
        $user->setPassword(
            $this->passwordHasher->hashPassword(
                $user,
                $password
            )
        );

        // Validation des données
        $errors = $validator->validate($user);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }
            $this->addFlash('error', implode(', ', $errorMessages));
            return $this->redirectToRoute('app_register_get');
        }

        // Validation du rôle
        if (!in_array($role, ['ROLE_RESTAURANT_OWNER', 'ROLE_CLIENT'])) {
            $this->addFlash('error', 'Invalid role selected');
            return $this->redirectToRoute('app_register_get');
        }
        $user->setRoles([$role]);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $this->addFlash('success', 'User registered successfully');
        return $this->redirectToRoute('app_register_get');
    }
}

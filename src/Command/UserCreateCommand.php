<?php

namespace App\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:user:create',
    description: 'Create a new user.',
)]
class UserCreateCommand extends Command
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly ManagerRegistry $managerRegistry,
        private readonly UserRepository $userRepository
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('email', InputArgument::REQUIRED, 'The user email')
            ->addArgument('password', InputArgument::REQUIRED, 'The user password')
            ->addOption('admin', null, InputOption::VALUE_NONE, 'Set the user as admin')
            ->addOption('superadmin', null, InputOption::VALUE_NONE, 'Set the user as super admin');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $email = $input->getArgument('email');
        $password = $input->getArgument('password');

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $io->error(sprintf('You passed an invalid email: %s', $email));

            return Command::FAILURE;
        }
        if (strlen((string) $password) < 8) {
            $io->error('You passed a short password use 8 characters');

            return Command::FAILURE;
        }
        if (null !== $this->userRepository->findOneBy(['email' => $email])) {
            $io->error(sprintf('A user already exists with the email: %s', $email));

            return Command::FAILURE;
        }
        $user = new User();
        $user->setEmail($email);
        $user->setPassword(
            $this->passwordHasher->hashPassword(
                $user,
                trim($password)
            )
        );

        $user->setRoles(['ROLE_USER']);
        if ($input->getOption('admin')) {
            $user->setRoles(['ROLE_ADMIN']);
        }
        if ($input->getOption('superadmin')) {
            $user->setRoles(['ROLE_SUPER_ADMIN']);
        }
        $em = $this->managerRegistry->getManager();
        $em->persist($user);
        $em->flush();

        $io->success('You have successfully created a new user.');

        return Command::SUCCESS;
    }
}

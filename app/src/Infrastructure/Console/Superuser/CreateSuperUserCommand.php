<?php

declare(strict_types=1);

namespace App\Infrastructure\Console\Superuser;

use App\Domain\Common\Exception\EmailIsInvalidException;
use App\Domain\Common\ValueObject\Email;
use App\Domain\User\Enum\RolesEnum;
use App\Domain\User\Exception\UserWithThisEmailAlreadyExistsException;
use App\Domain\User\Repository\UserRepository;
use App\Domain\User\Service\CreateUserService;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateSuperUserCommand extends Command
{
    public function __construct(
        private CreateUserService $createUserService,
        private EntityManagerInterface $entityManager,
        private UserRepository $userRepository,
    ) {
        parent::__construct();
    }

    public function configure(): void
    {
        $this->setName('app:create-superuser');
        $this->setDescription('Creates superuser.');
        $this->addArgument('email', InputArgument::REQUIRED);
        $this->addArgument('password', InputArgument::REQUIRED);
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $isSuperUserExistInSystem = $this->userRepository->isSuperUserExistInSystem();

        if ($isSuperUserExistInSystem) {
            $output->writeln('Superuser already exists in system.');

            return Command::SUCCESS;
        }

        try {
            /**
             * @psalm-var non-empty-string $emailAsString
             */
            $emailAsString = $input->getArgument('email');
            $email = Email::tryCreateFromString($emailAsString);
        } catch (EmailIsInvalidException) {
            $output->writeln('<error>Email is invalid</error>');

            return Command::FAILURE;
        }

        /**
         * @psalm-var non-empty-string $password
         */
        $password = $input->getArgument('password');

        try {
            $this->createUserService->create(
                name: 'superuser',
                email: $email,
                password: $password,
                role: RolesEnum::SUPERUSER,
                birthDate: new DateTimeImmutable(),
            );
        } catch (UserWithThisEmailAlreadyExistsException) {
            $output->writeln('<error>Email already exists</error>');

            return Command::FAILURE;
        }

        $this->entityManager->flush();

        return Command::SUCCESS;
    }
}

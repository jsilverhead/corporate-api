<?php

declare(strict_types=1);

namespace App\Tests\Functional\Infrastructure\Console;

use App\Domain\Common\ValueObject\Email;
use App\Domain\Employee\Enum\RolesEnum;
use App\Domain\Employee\Repository\EmployeeRepository;
use App\Infrastructure\Console\Superuser\CreateSuperUserCommand;
use App\Tests\BaseWebTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * @internal
 *
 * @coversNothing
 */
#[CoversClass(CreateSuperUserCommand::class)]
final class CreateSuperUserCommandTest extends BaseWebTestCase
{
    public function testSuccess(): void
    {
        $kernel = self::bootKernel();
        $application = new Application($kernel);

        $command = $application->find('app:create-superuser');
        $commandTester = new CommandTester($command);

        $email = 'superuser@example.com';
        $password = 'superuser12345';
        $commandTester->execute(
            input: [
                'email' => $email,
                'password' => $password,
            ],
        );

        $commandTester->assertCommandIsSuccessful();

        $userEmail = Email::tryCreateFromString($email);
        $user = $this->getService(EmployeeRepository::class)->getByEmail($userEmail);

        self::assertNotNull($user);
        self::assertTrue(RolesEnum::SUPERUSER === $user->role);
    }
}

<?php

declare(strict_types=1);

namespace App\Tests\Builder;

use App\Domain\Department\Department;
use App\Domain\Department\Service\CreateDepartmentService;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;

class DepartmentBuilder
{
    private ?DateTimeImmutable $deletedAt = null;

    /** @psalm-var non-empty-string|null */
    private ?string $name = null;

    public function __construct(
        private EntityManagerInterface $entityManager,
        private CreateDepartmentService $createDepartmentService,
    ) {
    }

    public function asDeleted(): self
    {
        $this->deletedAt = new DateTimeImmutable();

        return $this;
    }

    public function build(): Department
    {
        $department = $this->createDepartmentService->create($this->name ?? uniqid('Департамент_', true));

        if (null !== $this->deletedAt) {
            $department->deletedAt = $this->deletedAt;
        }

        $this->entityManager->flush();

        return $department;
    }

    /**
     * @psalm-param non-empty-string $name
     */
    public function withName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
}

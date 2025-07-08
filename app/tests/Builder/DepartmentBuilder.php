<?php

declare(strict_types=1);

namespace App\Tests\Builder;

use App\Domain\Department\Department;
use App\Domain\Department\Service\CreateDepartmentService;
use Doctrine\ORM\EntityManagerInterface;

class DepartmentBuilder
{
    /** @psalm-var non-empty-string|null */
    private ?string $name = null;

    public function __construct(
        private EntityManagerInterface $entityManager,
        private CreateDepartmentService $createDepartmentService,
    ) {
    }

    public function build(): Department
    {
        $department = $this->createDepartmentService->create($this->name ?? uniqid('Департамент_', true));

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

<?php

declare(strict_types=1);

namespace App\Domain\Vacation\Repository;

use App\Domain\Common\Exception\EntityNotFound\EntityNotFoundEnum;
use App\Domain\Common\Exception\EntityNotFound\EntityNotFoundException;
use App\Domain\Common\Repository\ServiceEntityRepository;
use App\Domain\Employee\Employee;
use App\Domain\Employee\Exception\EmployeeIsNotSupervisingAnyDepartmentException;
use App\Domain\Vacation\Vacation;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

/**
 * @extends ServiceEntityRepository<Vacation>
 */
class VacationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct(registry: $registry, entityClass: Vacation::class);
    }

    public function add(Vacation $vacation): void
    {
        $this->getEntityManager()->persist($vacation);
    }

    public function getByIdOwnedByEmployeeOrFail(Uuid $id, Employee $employee): Vacation
    {
        /** @psalm-var Vacation|null $vacation */
        $vacation = $this->createQueryBuilder('vc')
            ->where('vc.id = :id')
            ->setParameter('id', $id, UuidType::NAME)
            ->andWhere('vc.employee = :employeeId')
            ->setParameter('employeeId', $employee->id, UuidType::NAME)
            ->getQuery()
            ->getOneOrNullResult();

        if (null === $vacation) {
            throw new EntityNotFoundException(EntityNotFoundEnum::VACATION);
        }

        return $vacation;
    }

    public function getVacationByIdSupervisedByEmployeeOrFail(Uuid $id, Employee $employee): Vacation
    {
        if (null === $employee->supervising) {
            throw new EmployeeIsNotSupervisingAnyDepartmentException();
        }

        /** @psalm-var Vacation|null $vacation */
        $vacation = $this->createQueryBuilder('v')
            ->join('v.employee', 'e')
            ->join(
                'e.department',
                'd',
                'WITH',
                'd.id = (
        SELECT s.id
        FROM App\Domain\Employee\Employee e2
        JOIN e2.supervising s
        WHERE e2.id = :employeeId
    )',
            )
            ->setParameter('employeeId', $employee->id, UuidType::NAME)
            ->andWhere('v.id = :id')
            ->setParameter('id', $id, UuidType::NAME)
            ->getQuery()
            ->getOneOrNullResult();

        if (null === $vacation) {
            throw new EntityNotFoundException(EntityNotFoundEnum::VACATION);
        }

        return $vacation;
    }
}

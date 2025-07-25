<?php

declare(strict_types=1);

namespace App\Domain\Vacation\Repository;

use App\Domain\Common\Exception\EntityNotFound\EntityNotFoundEnum;
use App\Domain\Common\Exception\EntityNotFound\EntityNotFoundException;
use App\Domain\Common\Repository\ServiceEntityRepository;
use App\Domain\Common\ValueObject\Period;
use App\Domain\Employee\Employee;
use App\Domain\Vacation\Vacation;
use Doctrine\DBAL\Types\Types;
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

    public function getById(Uuid $id): ?Vacation
    {
        /**
         * @psalm-var Vacation|null $vacation
         */
        $vacation = $this->createQueryBuilder('vc')
            ->where('vc.id = :id')
            ->setParameter('id', $id, UuidType::NAME)
            ->getQuery()
            ->getOneOrNullResult();

        return $vacation;
    }

    public function getByIdAndEmployeeOrFail(Employee $employee, Uuid $id): Vacation
    {
        /**
         * @psalm-var Vacation|null $vacation
         */
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

    public function getByIdOrFail(Uuid $id): Vacation
    {
        $vacation = $this->getById($id);

        if (null === $vacation) {
            throw new EntityNotFoundException(EntityNotFoundEnum::VACATION);
        }

        return $vacation;
    }

    public function getVacationWithPeriodCrossingCurrentPeriod(Employee $employee, Period $period): ?Vacation
    {
        /**
         * @psalm-var Vacation|null $vacation
         */
        $vacation = $this->createQueryBuilder('vc')
            ->where('vc.employee = :employeeId')
            ->setParameter('employeeId', $employee->id, UuidType::NAME)
            ->andWhere('vc.fromDate BETWEEN :start AND :end OR vc.toDate BETWEEN :start AND :end')
            ->setParameter('start', $period->fromDate, Types::DATETIME_IMMUTABLE)
            ->setParameter('end', $period->toDate, Types::DATETIME_IMMUTABLE)
            ->getQuery()
            ->getOneOrNullResult();

        return $vacation;
    }
}

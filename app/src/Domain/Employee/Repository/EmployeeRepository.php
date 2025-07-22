<?php

declare(strict_types=1);

namespace App\Domain\Employee\Repository;

use App\Domain\Common\Exception\EntityNotFound\EntityNotFoundEnum;
use App\Domain\Common\Exception\EntityNotFound\EntityNotFoundException;
use App\Domain\Common\Repository\ServiceEntityRepository;
use App\Domain\Common\ValueObject\Email;
use App\Domain\Department\Department;
use App\Domain\Employee\Employee;
use App\Domain\Employee\Enum\RolesEnum;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

/**
 * @extends ServiceEntityRepository<Employee>
 */
class EmployeeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct(registry: $registry, entityClass: Employee::class);
    }

    public function add(Employee $employee): void
    {
        $this->getEntityManager()->persist($employee);
    }

    public function eraseDepartmentAndSupervising(Department $department): void
    {
        $this->createQueryBuilder('e')
            ->update()
            ->set('e.department', ':null')
            ->setParameter('null', null)
            ->where('e.department = :id')
            ->setParameter('id', $department->id, UuidType::NAME)
            ->getQuery()
            ->execute();

        $this->createQueryBuilder('e')
            ->update()
            ->set('e.supervising', ':null')
            ->setParameter('null', null)
            ->where('e.supervising = :id')
            ->setParameter('id', $department->id, UuidType::NAME)
            ->getQuery()
            ->execute();
    }

    public function getByEmail(Email $email): ?Employee
    {
        /**
         * @psalm-var Employee|null $employee
         */
        $employee = $this->createQueryBuilder('em')
            ->where('em.email = :email')
            ->setParameter('email', $email->email, Types::STRING)
            ->getQuery()
            ->getOneOrNullResult();

        return $employee;
    }

    public function getByEmailOrFail(Email $email): Employee
    {
        $employee = $this->getByEmail($email);

        if (null === $employee) {
            throw new EntityNotFoundException(EntityNotFoundEnum::EMPLOYEE);
        }

        return $employee;
    }

    public function getById(Uuid $id): ?Employee
    {
        /**
         * @psalm-var Employee|null $employee
         */
        $employee = $this->createQueryBuilder('em')
            ->where('em.id = :id')
            ->setParameter('id', $id, UuidType::NAME)
            ->getQuery()
            ->getOneOrNullResult();

        return $employee;
    }

    public function getByIdOrFail(Uuid $id): Employee
    {
        $employee = $this->getById($id);

        if (null === $employee) {
            throw new EntityNotFoundException(EntityNotFoundEnum::EMPLOYEE);
        }

        return $employee;
    }

    public function getByIdWithDepartmentOrFail(Uuid $id): Employee
    {
        /**
         * @psalm-var Employee|null $employee
         */
        $employee = $this->createQueryBuilder('em')
            ->select('em', 'd')
            ->leftJoin('em.department', 'd')
            ->where('em.id = :id')
            ->setParameter('id', $id, UuidType::NAME)
            ->getQuery()
            ->getOneOrNullResult();

        if (null === $employee) {
            throw new EntityNotFoundException(EntityNotFoundEnum::EMPLOYEE);
        }

        return $employee;
    }

    public function isEmployeeWithEmailExists(Email $email): bool
    {
        return (bool) $this->createQueryBuilder('em')
            ->select('1')
            ->where('em.email = :email')
            ->setParameter('email', $email->email, Types::STRING)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function isSuperuserExistInSystem(): bool
    {
        return (bool) $this->createQueryBuilder('em')
            ->select('1')
            ->where('em.role = :role')
            ->setParameter('role', RolesEnum::SUPERUSER->value, Types::STRING)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @psalm-param list<string>|null $searchWords
     *
     * @psalm-return Paginator<Employee>
     */
    public function listEmployees(int $count, int $offset, ?array $searchWords): Paginator
    {
        $employees = $this->createQueryBuilder('em')
            ->select('em', 'd')
            ->leftJoin('em.department', 'd')
            ->orderBy('em.createdAt', 'DESC')
            ->setFirstResult($offset)
            ->setMaxResults($count);

        if (null !== $searchWords) {
            foreach ($searchWords as $index => $searchWord) {
                $paramName = "word_{$index}";
                $employees
                    ->orWhere('LOWER(em.name) LIKE :' . $paramName)
                    ->setParameter($paramName, '%' . mb_strtolower($searchWord) . '%', Types::STRING);
            }
        }

        $employees->andWhere('em.deletedAt IS NULL');

        /**
         * @psalm-var Paginator<Employee> $paginator
         */
        $paginator = new Paginator($employees->getQuery());

        return $paginator;
    }
}

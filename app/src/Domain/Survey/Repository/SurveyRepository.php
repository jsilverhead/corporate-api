<?php

declare(strict_types=1);

namespace App\Domain\Survey\Repository;

use App\Domain\Common\Exception\EntityNotFound\EntityNotFoundEnum;
use App\Domain\Common\Exception\EntityNotFound\EntityNotFoundException;
use App\Domain\Common\Repository\ServiceEntityRepository;
use App\Domain\Employee\Employee;
use App\Domain\Survey\Enum\StatusEnum;
use App\Domain\Survey\Survey;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

/**
 * @extends ServiceEntityRepository<Survey>
 */
class SurveyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Survey::class);
    }

    public function add(Survey $survey): void
    {
        $this->getEntityManager()->persist($survey);
    }

    public function getByIdAndApplier(Employee $employee, Uuid $surveyId): Survey
    {
        /**
         * @psalm-var Survey|null $survey
         */
        $survey = $this->createQueryBuilder('srv')
            ->where('srv.id = :id')
            ->setParameter('id', $surveyId, UuidType::NAME)
            ->andWhere('srv.employee = :employeeId')
            ->setParameter('employeeId', $employee->id, UuidType::NAME)
            ->andWhere('srv.isCompleted = FALSE')
            ->getQuery()
            ->getOneOrNullResult();

        if (null === $survey) {
            throw new EntityNotFoundException(EntityNotFoundEnum::SURVEY);
        }

        return $survey;
    }

    /**
     * @psalm-return Paginator<Survey>
     */
    public function listSurveys(int $count, int $offset, StatusEnum $status): Paginator
    {
        $surveys = $this->createQueryBuilder('srv')
            ->select('srv', 'e')
            ->join('srv.employee', 'e')
            ->orderBy('srv.createdAt', 'DESC')
            ->setFirstResult($offset)
            ->setMaxResults($count);

        if (StatusEnum::COMPLETED === $status) {
            $surveys->andWhere('srv.isCompleted = TRUE');
        }

        if (StatusEnum::INCOMPLETE === $status) {
            $surveys->andWhere('srv.isCompleted = FALSE');
        }

        /**
         * @psalm-var Paginator<Survey> $paginator
         */
        $paginator = new Paginator($surveys->getQuery());

        return $paginator;
    }
}

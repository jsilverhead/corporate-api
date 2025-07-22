<?php

declare(strict_types=1);

namespace App\Domain\Survey\Repository;

use App\Domain\Common\Repository\ServiceEntityRepository;
use App\Domain\Survey\Question;
use App\Domain\Survey\Survey;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

/**
 * @extends ServiceEntityRepository<Question>
 */
class QuestionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Question::class);
    }

    public function add(Question $question): void
    {
        $this->getEntityManager()->persist($question);
    }

    /**
     * @psalm-param list<Uuid> $questionIds
     *
     * @psalm-return list<Question>
     */
    public function getByIdsAndSurvey(Survey $survey, array $questionIds): array
    {
        /**
         * @psalm-var list<Question> $foundQuestions
         */
        $foundQuestions = $this->createQueryBuilder('q')
            ->leftJoin('q.template', 't')
            ->andWhere('t.id = :templateId')
            ->setParameter('templateId', $survey->template->id, UuidType::NAME)
            ->andWhere('q.id IN (:questions)')
            ->setParameter('questions', $questionIds)
            ->getQuery()
            ->getResult();

        return $foundQuestions;
    }
}

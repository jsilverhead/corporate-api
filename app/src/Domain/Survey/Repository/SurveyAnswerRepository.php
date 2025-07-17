<?php

declare(strict_types=1);

namespace App\Domain\Survey\Repository;

use App\Domain\Common\Repository\ServiceEntityRepository;
use App\Domain\Survey\Question;
use App\Domain\Survey\Survey;
use App\Domain\Survey\SurveyAnswer;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Types\UuidType;

/**
 * @extends ServiceEntityRepository<SurveyAnswer>
 */
class SurveyAnswerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SurveyAnswer::class);
    }

    public function add(SurveyAnswer $answer): void
    {
        $this->getEntityManager()->persist($answer);
    }

    public function isQuestionAlreadyHaveAnswer(Survey $survey, Question $question): bool
    {
        return (bool) $this->createQueryBuilder('a')
            ->select('1')
            ->where('a.survey = :surveyId')
            ->setParameter('surveyId', $survey->id, UuidType::NAME)
            ->andWhere('a.question = :questionId')
            ->setParameter('questionId', $question->id, UuidType::NAME)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
}

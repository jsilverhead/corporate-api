<?php

declare(strict_types=1);

namespace App\Domain\Survey\Repository;

use App\Domain\Common\Repository\ServiceEntityRepository;
use App\Domain\Survey\Question;
use Doctrine\Persistence\ManagerRegistry;

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
}

<?php

declare(strict_types=1);

namespace App\Domain\Survey\Repository;

use App\Domain\Common\Repository\ServiceEntityRepository;
use App\Domain\Survey\SurveyTemplate;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

/**
 * @extends ServiceEntityRepository<SurveyTemplate>
 */
class SurveyTemplateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SurveyTemplate::class);
    }

    public function add(SurveyTemplate $surveyTemplate): void
    {
        $this->getEntityManager()->persist($surveyTemplate);
    }

    public function getById(Uuid $id): ?SurveyTemplate
    {
        /**
         * @psalm-var SurveyTemplate|null $template
         */
        $template = $this->createQueryBuilder('t')
            ->where('t.id = :id')
            ->setParameter('id', $id, UuidType::NAME)
            ->getQuery()
            ->getOneOrNullResult();

        return $template;
    }

    public function getByIdOrFail(Uuid $id): SurveyTemplate
    {
        $template = $this->getById($id);

        if (null === $template) {
            throw new EntityNotFoundException();
        }

        return $template;
    }
}

<?php

declare(strict_types=1);

namespace App\Domain\Survey\Repository;

use App\Domain\Common\Exception\EntityNotFound\EntityNotFoundEnum;
use App\Domain\Common\Exception\EntityNotFound\EntityNotFoundException;
use App\Domain\Common\Repository\ServiceEntityRepository;
use App\Domain\Survey\SurveyTemplate;
use Doctrine\ORM\Tools\Pagination\Paginator;
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

    /**
     * @psalm-suppress PossiblyUnusedMethod
     */
    public function getByIdOrFail(Uuid $id): SurveyTemplate
    {
        $template = $this->getById($id);

        if (null === $template) {
            throw new EntityNotFoundException(EntityNotFoundEnum::SURVEY_TEMPLATE);
        }

        return $template;
    }

    public function getByIdWithoutDeletedOrFail(Uuid $id): SurveyTemplate
    {
        /**
         * @psalm-var SurveyTemplate|null $template
         */
        $template = $this->createQueryBuilder('t')
            ->where('t.id = :id')
            ->setParameter('id', $id, UuidType::NAME)
            ->andWhere('t.deletedAt IS NULL')
            ->getQuery()
            ->getOneOrNullResult();

        if (null === $template) {
            throw new EntityNotFoundException(EntityNotFoundEnum::SURVEY_TEMPLATE);
        }

        return $template;
    }

    /**
     * @psalm-return Paginator<SurveyTemplate>
     */
    public function listSurveyTemplates(int $count, int $offset): Paginator
    {
        $templates = $this->createQueryBuilder('t')
            ->orderBy('t.createdAt', 'DESC')
            ->where('t.deletedAt IS NULL')
            ->setFirstResult($offset)
            ->setMaxResults($count)
            ->getQuery();

        /**
         * @psalm-var Paginator<SurveyTemplate> $paginator
         */
        $paginator = new Paginator($templates);

        return $paginator;
    }
}

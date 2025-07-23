<?php

declare(strict_types=1);

namespace App\Domain\Vacation\Repository;

use App\Domain\Common\Repository\ServiceEntityRepository;
use App\Domain\Vacation\Vacation;
use Doctrine\Persistence\ManagerRegistry;

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
}

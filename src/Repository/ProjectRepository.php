<?php

namespace App\Repository;

use App\Entity\Project;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Query\QueryException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Project>
 *
 * @method Project|null find($id, $lockMode = null, $lockVersion = null)
 * @method Project|null findOneBy(array $criteria, array $orderBy = null)
 * @method Project[]    findAll()
 * @method Project[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProjectRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Project::class);
    }

    public function existsByTitle(string $title): bool
    {
        return null !== $this->findOneBy(['title' => $title]);
    }

    /**
     * @return Project[]
     */
    public function findAllSortedByDeadline(): array
    {
        return $this->findBy([], ['deadline' => Criteria::ASC]);
    }

    /**
     * @param array $criteria
     * @param array|null $orderBy
     * @param int|null $limit
     * @param int|null $offset
     * @return Project[]
     */
    public function findFilteredAndSorted(array $criteria = [], ?array $orderBy = null, ?int $limit = null, ?int $offset = null): array
    {
        return $this->findBy($criteria, $orderBy, $limit, $offset);
    }

    public function existsById(int $id): bool
    {
        return null !== $this->find($id);
    }
}

<?php

namespace App\Repository;

use App\Entity\Project;
use App\Entity\Task;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Task>
 *
 * @method Task|null find($id, $lockMode = null, $lockVersion = null)
 * @method Task|null findOneBy(array $criteria, array $orderBy = null)
 * @method Task[]    findAll()
 * @method Task[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TaskRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Task::class);
    }

    public function existsByTitle(string $title): bool
    {
        return null !== $this->findOneBy(['title' => $title]);
    }

    /**
     * @param array $criteria
     * @param array|null $orderBy
     * @param int|null $limit
     * @param int|null $offset
     * @return Task[]
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

<?php

namespace App\Repository;

use App\Entity\Task;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Repository pour l'entité Task
 * 
 * Cette classe gère les opérations de base de données liées aux tâches,
 * notamment la recherche et le tri des tâches selon différents critères.
 *
 * @extends ServiceEntityRepository<Task>
 *
 * @method Task|null find($id, $lockMode = null, $lockVersion = null)
 * @method Task|null findOneBy(array $criteria, array $orderBy = null)
 * @method Task[]    findAll()
 * @method Task[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TaskRepository extends ServiceEntityRepository
{
    /**
     * Constantes définissant les options de tri disponibles
     */
    public const SORT_BY_DATE_ASC = 'date_asc';       // Tri par date (croissant)
    public const SORT_BY_DATE_DESC = 'date_desc';     // Tri par date (décroissant)
    public const SORT_BY_STATUS_DONE = 'status_done'; // Tâches terminées d'abord
    public const SORT_BY_STATUS_PENDING = 'status_pending'; // Tâches en cours d'abord

    /**
     * Constructeur du TaskRepository
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Task::class);
    }

    /**
     * Récupère toutes les tâches terminées
     * 
     * @return Task[] Tableau des tâches terminées triées par date de création décroissante
     */
    public function findCompleted(): array
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.isDone = :val')
            ->setParameter('val', true)
            ->orderBy('t.createdAt', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * Récupère toutes les tâches en cours
     * 
     * @return Task[] Tableau des tâches en cours triées par date de création décroissante
     */
    public function findPending(): array
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.isDone = :val')
            ->setParameter('val', false)
            ->orderBy('t.createdAt', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * Récupère toutes les tâches triées par date de création décroissante
     * 
     * @return Task[] Tableau de toutes les tâches
     */
    public function findAllSortedByDate(): array
    {
        return $this->createQueryBuilder('t')
            ->orderBy('t.createdAt', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * Récupère toutes les tâches triées selon le critère spécifié
     * 
     * @param string|null $sortBy Critère de tri (utiliser les constantes de classe)
     * @return Task[] Tableau des tâches triées selon le critère demandé
     */
    public function findAllSorted(string $sortBy = null): array
    {
        $queryBuilder = $this->createQueryBuilder('t');

        switch ($sortBy) {
            case self::SORT_BY_DATE_ASC:
                // Tri par date croissante (plus anciennes d'abord)
                $queryBuilder->orderBy('t.createdAt', 'ASC');
                break;

            case self::SORT_BY_DATE_DESC:
                // Tri par date décroissante (plus récentes d'abord)
                $queryBuilder->orderBy('t.createdAt', 'DESC');
                break;

            case self::SORT_BY_STATUS_DONE:
                // Tâches terminées d'abord, puis tri par date
                $queryBuilder
                    ->orderBy('t.isDone', 'DESC')
                    ->addOrderBy('t.createdAt', 'DESC');
                break;

            case self::SORT_BY_STATUS_PENDING:
                // Tâches en cours d'abord, puis tri par date
                $queryBuilder
                    ->orderBy('t.isDone', 'ASC')
                    ->addOrderBy('t.createdAt', 'DESC');
                break;

            default:
                // Tri par défaut : plus récentes d'abord
                $queryBuilder->orderBy('t.createdAt', 'DESC');
        }

        return $queryBuilder
            ->getQuery()
            ->getResult();
    }
}

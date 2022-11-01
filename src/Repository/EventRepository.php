<?php

namespace App\Repository;

use App\Entity\Event;
use App\Entity\Restorer;
use App\Entity\User;
use App\Event\Model\EventSearch;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Event>
 *
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

    public function add(Event $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Event $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return Event[] Returns an array of Event objects
     */
    public function search(EventSearch $search): array
    {
        $now = new DateTime('midnight');
        $qb = $this
            ->createQueryBuilder('e')
            ->select('r', 'e')
            ->join('e.restaurant', 'r')
            ->leftJoin('e.attendees', 'a')
            ->where('e.date >= :now')
            ->setParameter('now', $now)
            ->orderBy('e.date', 'ASC');

        if ($search->getDate()) {
            $qb = $qb
                ->andWhere('e.date = :date')
                ->setParameter('date', $search->getDate());
        }

        if ($search->getMeal()) {
            $qb = $qb
                ->andWhere('e.meal = :meal')
                ->setParameter('meal', $search->getMeal());
        }

        if ($search->getType()) {
            $qb = $qb
                ->andWhere('r.foodType = :type')
                ->setParameter('type', $search->getType());
        }

        if ($search->getRestaurant()) {
            $qb = $qb
                ->andWhere('r.name like :name')
                ->setParameter('name', "%{$search->getRestaurant()}%");
        }

        if ($search->getZone()) {
            $qb = $qb
                ->andWhere('r.zone = :zone')
                ->setParameter('zone', $search->getZone());
        }
        if ($search->getAttendees()) {
            $qb = $qb
                ->having('COUNT(e.id) >= 2')
                ->groupBy('e.id');
        }

        $query = $qb->getQuery();
        return $query->execute();
    }

    public function display(): array
    {
        $now = new DateTime();

        $qb = $this->createQueryBuilder('e')
            ->select('e')
            ->where('e.date >= :now')
            ->setParameter('now', $now)
            ->setMaxResults(4);

        $query = $qb->getQuery();

        return $query->execute();
    }

    public function nextDateEvent(Event $event): array
    {
        $now = new DateTime('midnight');

        return $this->createQueryBuilder('e')
            ->select('e')
            ->andWhere('e.restaurant = :restaurant')
            ->andWhere('e.date >= :date')
            ->setParameters([
                'restaurant' => $event->getRestaurant(),
                'date' => $now,
            ])
            ->getQuery()->execute();
    }

    /**
     * @return Paginator|Event[]
     */
    public function getEventsPaginator(int $page, int $limit): Paginator
    {
        $now = new DateTime('midnight');
        $start = ($page - 1) * $limit;
        $query = $this->createQueryBuilder('e')
            ->andWhere('e.date >= :date')
            ->setParameters([
                'date' => $now,
            ])
            ->orderBy('e.date', 'ASC')
            ->setFirstResult($start)
            ->setMaxResults($limit)
            ->getQuery();

        return new Paginator($query);
    }

    /**
     * retourn les events (prochains) dont l'user est inscrit
     * @return Event[]
     */
    public function getNextEventAsAttendee(User $user): array
    {
        $now = new DateTime('midnight');

        return $this->createQueryBuilder('e')
            ->join('e.attendees', 'a')
            ->andWhere('a.id = :user_id')
            ->andWhere('e.date >= :date')
            ->setParameters([
                'user_id' => $user->getId(),
                'date' => $now,
            ])
            ->getQuery()->execute();
    }

    public function getPastEventAsAttendee(User $user): array
    {
        $now = new DateTime('midnight');

        return $this->createQueryBuilder('e')
            ->join('e.attendees', 'a')
            ->andWhere('a.id = :user_id')
            ->andWhere('e.date < :date')
            ->setParameters([
                'user_id' => $user->getId(),
                'date' => $now,
            ])
            ->getQuery()->execute();
    }

    public function getNextEventAsRestorer(Restorer $restorer): array
    {
        $now = new DateTime('midnight');

        return $this->createQueryBuilder('e')
            ->join('e.restaurant', 'r')
            ->andWhere('r.restorer = :restorer_id')
            ->andWhere('e.date >= :date')
            ->setParameters([
                'restorer_id' => $restorer->getId(),
                'date' => $now,
            ])
            ->getQuery()->execute();
    }

    public function getPastEventAsRestorer(Restorer $restorer): array
    {
        $now = new DateTime('midnight');

        return $this->createQueryBuilder('e')
            ->join('e.restaurant', 'r')
            ->andWhere('r.restorer = :restorer_id')
            ->andWhere('e.date < :date')
            ->setParameters([
                'restorer_id' => $restorer->getId(),
                'date' => $now,
            ])
            ->getQuery()->execute();
    }
}

<?php

namespace Pos\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Pos\Entity\Sale;

class SaleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sale::class);
    }

    public function findDebtNotes(): array
    {
        return $this->createQueryBuilder('n')
            ->leftjoin('n.client', 'c')
            ->andWhere('c.id is not null')
            ->getQuery()
            ->execute();
    }

    public function delete(Sale $sale): void
    {
        $originalEventListeners = [];

        // cycle through all registered event listeners
        foreach ($this->_em->getEventManager()->getListeners() as $eventName => $listeners) {
            foreach ($listeners as $listener) {
                if ($listener instanceof \Gedmo\SoftDeleteable\SoftDeleteableListener) {

                    // store the event listener, that gets removed
                    $originalEventListeners[$eventName] = $listener;

                    // remove the SoftDeletableSubscriber event listener
                    $this->_em->getEventManager()->removeEventListener($eventName, $listener);
                }
            }
        }

        // remove the entity
        $this->_em->remove($sale);
        $this->_em->flush();

        // re-add the removed listener back to the event-manager
        foreach ($originalEventListeners as $eventName => $listener) {
            $this->_em->getEventManager()->addEventListener($eventName, $listener);
        }
    }
}
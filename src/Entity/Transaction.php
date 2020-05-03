<?php

namespace Pos\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="transaction")
 * @ORM\Entity(repositoryClass="Pos\Repository\TransactionRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Transaction
{
    const TYPE_SALE = 'sale';
    const TYPE_ORDER = 'order';

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    public int $id;

    /**
     * @ORM\Column(type="datetime")
     */
    public DateTime $created;

    /**
     * @ORM\Column(type="datetime")
     */
    public DateTime $updated;

    /**
     * @ORM\Column(type="string")
     */
    public ?string $type = null;

    /**
     * @ORM\Column(type="date")
     */
    public DateTime $date;

    /**
     * @ORM\Column(type="string")
     */
    public ?string $item = null;

    /**
     * @ORM\Column(type="float")
     */
    public ?float $price = 0;

    /**
     * @ORM\Column(type="integer")
     */
    public ?int $receiptNumber = null;

    public function __construct()
    {
        $this->date = new DateTime();
    }

    /**
     * @ORM\PrePersist()
     */
    public function updateCreated(): void
    {
        $this->created = new DateTime();
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function updateUpdated(): void
    {
        $this->updated = new DateTime();
    }
}

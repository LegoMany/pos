<?php

declare(strict_types=1);

namespace Pos\Entity;

use Pos\Repository\TransactionRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TransactionRepository::class)]
#[ORM\Table(name: 'transaction')]
#[ORM\HasLifecycleCallbacks]
class Transaction
{
    public const TYPE_SALE = 'sale';
    public const TYPE_ORDER = 'order';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    public ?int $id = null;

    #[ORM\Column(type: 'datetime')]
    public DateTime $created;

    #[ORM\Column(type: 'datetime')]
    public DateTime $updated;

    #[ORM\Column(type: 'string')]
    public ?string $type = null;

    #[ORM\Column(type: 'date')]
    public DateTime $date;

    #[ORM\Column(type: 'string')]
    public ?string $item = null;

    #[ORM\Column(type: 'float')]
    public ?float $price = 0;

    /**
     * Used internally when creating the print view
     */
    public ?int $receiptNumber = null;

    public function __construct()
    {
        $this->date = new DateTime();
    }

    #[ORM\PrePersist]
    public function updateCreated(): void
    {
        $this->created = new DateTime();
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function updateUpdated(): void
    {
        $this->updated = new DateTime();
    }
}

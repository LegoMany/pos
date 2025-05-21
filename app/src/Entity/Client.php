<?php

declare(strict_types=1);

namespace Pos\Entity;

use Pos\Repository\ClientRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClientRepository::class)]
#[ORM\Table(name: 'client')]
class Client
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    public ?int $id = null;

    #[ORM\Column(type: 'string')]
    public ?string $name = null;

    #[ORM\OneToOne(targetEntity: Sale::class, mappedBy: 'client')]
    public ?Sale $sale = null;
}
<?php

declare(strict_types=1);

namespace Pos\Entity;

use Pos\Repository\ItemRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;

#[ORM\Entity(repositoryClass: ItemRepository::class)]
#[ORM\Table(name: 'item')]
#[Gedmo\SoftDeleteable(fieldName: 'deletedAt', hardDelete: false)]
class Item
{
    use SoftDeleteableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    public ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Product::class)]
    #[ORM\JoinColumn(unique: false)]
    public ?Product $product = null;

    #[ORM\Column(type: 'integer')]
    public ?int $quantity = null;

    #[ORM\ManyToOne(targetEntity: Sale::class, inversedBy: 'items')]
    public ?Sale $sale = null;
}
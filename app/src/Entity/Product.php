<?php

declare(strict_types=1);

namespace Pos\Entity;

use Pos\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[ORM\Table(name: 'product')]
#[Gedmo\SoftDeleteable(fieldName: 'deletedAt', hardDelete: false)]
class Product
{
    use SoftDeleteableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    public ?int $id = null;

    #[ORM\Column(type: 'string')]
    public ?string $name = null;

    #[ORM\Column(type: 'float')]
    public ?float $price = null;

    #[ORM\ManyToOne(targetEntity: Category::class, inversedBy: 'products')]
    public ?Category $category = null;
}

<?php

declare(strict_types=1);

namespace Pos\Entity;

use Pos\Repository\CategoryRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
#[ORM\Table(name: 'category')]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    public ?int $id = null;

    #[ORM\Column(type: 'string')]
    public ?string $name = null;

    #[ORM\OneToMany(targetEntity: Product::class, mappedBy: 'category')]
    public ?Collection $products = null;
}
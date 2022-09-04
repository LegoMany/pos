<?php

namespace Pos\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="product")
 * @ORM\Entity(repositoryClass="Pos\Repository\ProductRepository")
 */
class Product
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    public ?int $id = null;

    /**
     * @ORM\Column(type="string")
     */
    public ?string $name;

    /**
     * @ORM\Column(type="float")
     */
    public ?float $price;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="products")
     */
    public ?Category $category;
}

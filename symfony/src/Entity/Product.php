<?php

namespace Pos\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;

/**
 * @ORM\Table(name="product")
 * @ORM\Entity(repositoryClass="Pos\Repository\ProductRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", hardDelete=false)
 */
class Product
{
    use SoftDeleteableEntity;

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

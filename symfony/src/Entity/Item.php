<?php

namespace Pos\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;

/**
 * @ORM\Table(name="item")
 * @ORM\Entity(repositoryClass="Pos\Repository\ItemRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", hardDelete=false)
 */
class Item
{
    use SoftDeleteableEntity;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    public ?int $id = null;

    /**
     * @ORM\ManyToOne(targetEntity=Product::class)
     * @ORM\JoinColumn(unique=false)
     */
    public ?Product $product;

    /**
     * @ORM\Column(type="integer")
     */
    public ?int $quantity;

    /**
     * @ORM\ManyToOne(targetEntity=Sale::class, inversedBy="items")
     */
    public ?Sale $sale;
}

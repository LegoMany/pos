<?php

namespace Pos\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="item")
 * @ORM\Entity(repositoryClass="Pos\Repository\ItemRepository")
 */
class Item
{
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
     * @ORM\ManyToOne(targetEntity=DebtNote::class, inversedBy="items")
     */
    public ?DebtNote $debtNote;
}

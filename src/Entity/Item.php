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
     * @ORM\OneToOne(targetEntity=Product::class)
     */
    public ?Product $product;

    /**
     * @ORM\Column(type="integer")
     */
    public ?int $count;

    /**
     * @ORM\ManyToOne(targetEntity=DebtNote::class, inversedBy="items")
     */
    public ?DebtNote $debtNote;
}

<?php

namespace Pos\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;

/**
 * @ORM\Table(name="sale")
 * @ORM\Entity(repositoryClass="Pos\Repository\SaleRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", hardDelete=false)
 */
class Sale
{
    use SoftDeleteableEntity;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    public ?int $id = null;

    /**
     * @ORM\OneToOne(targetEntity=Client::class, inversedBy="sale")
     */
    public ?Client $client;

    /**
     * @ORM\OneToMany(targetEntity=Item::class, mappedBy="sale", cascade={"persist"})
     */
    public ?Collection $items;

    public function __construct()
    {
        $this->items = new ArrayCollection();
    }

    public function addProduct(Product $product): void
    {
        if (!empty($this->items)) {
            $existingItem = $this->items->filter(function (Item $item) use ($product) {
                return $item->product->id === $product->id;
            })->first();
            if ($existingItem instanceof Item) {
                $existingItem->quantity++;
                return;
            }
        }

        $item = new Item();
        $item->product = $product;
        $item->sale = $this;
        $item->quantity = 1;
        $this->items->add($item);
    }

    public function removeProduct(Product $product): void
    {
        $this->items->removeElement($this->items->filter(function (Item $item) use ($product) {
            return $item->product->id === $product->id;
        })->first());
    }

    public function getTotal(): float
    {
        return array_sum(array_map(function (Item $item) {
            return $item->product->price * $item->quantity;
        }, $this->items->toArray()));
    }
}

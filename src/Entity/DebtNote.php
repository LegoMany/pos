<?php

namespace Pos\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="debt_note")
 * @ORM\Entity(repositoryClass="Pos\Repository\DebtNoteRepository")
 */
class DebtNote
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    public ?int $id = null;

    /**
     * @ORM\OneToOne(targetEntity=Client::class, inversedBy="debtNote")
     */
    public ?Client $client;

    /**
     * @ORM\OneToMany(targetEntity=Item::class, mappedBy="debtNote", cascade="persist")
     */
    public ?Collection $items;

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
        $item->debtNote = $this;
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

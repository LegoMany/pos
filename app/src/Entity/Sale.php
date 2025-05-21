<?php

declare(strict_types=1);

namespace Pos\Entity;

use Pos\Repository\SaleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;

#[ORM\Entity(repositoryClass: SaleRepository::class)]
#[ORM\Table(name: 'sale')]
#[Gedmo\SoftDeleteable(fieldName: 'deletedAt', hardDelete: false)]
class Sale
{
    use SoftDeleteableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    public ?int $id = null;

    #[ORM\OneToOne(targetEntity: Client::class, inversedBy: 'sale')]
    public ?Client $client = null;

    #[ORM\OneToMany(targetEntity: Item::class, mappedBy: 'sale', cascade: ['persist'])]
    public Collection $items;

    public function __construct()
    {
        $this->items = new ArrayCollection();
    }

    public function addProduct(Product $product): void
    {
        if (!$this->items->isEmpty()) {
            $existingItem = $this->items->filter(function (Item $item) use ($product) {
                return $item->product?->id === $product->id;
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
            return $item->product?->id === $product->id;
        })->first());
    }

    public function getTotal(): float
    {
        return array_sum(array_map(function (Item $item) {
            return $item->product?->price * $item->quantity ?? 0;
        }, $this->items->toArray()));
    }
}

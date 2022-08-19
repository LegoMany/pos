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
     * @ORM\Column(type="string")
     */
    public ?string $client;

    /**
     * @ORM\OneToMany(targetEntity=Item::class, mappedBy="debtNote")
     */
    public ?Collection $items;
}

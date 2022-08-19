<?php

namespace Pos\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="client")
 * @ORM\Entity(repositoryClass="Pos\Repository\ClientRepository")
 */
class Client
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
     * @ORM\OneToOne(targetEntity=DebtNote::class, mappedBy="client")
     */
    public ?DebtNote $debtNote;
}

<?php

namespace Pos\Form\Transformer;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;

class NumberToLocalizedTransformer implements DataTransformerInterface
{
    protected EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function transform($value): string
    {
        $value = number_format($value, 2, ',', '.');
        return $value;
    }

    public function reverseTransform($value): float
    {
        $value = (float)str_replace(['.', ','], ['', '.'], $value);
        return $value;
    }
}
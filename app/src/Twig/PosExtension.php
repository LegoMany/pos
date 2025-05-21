<?php

namespace Pos\Twig;

use Pos\Utility\NumberUtility;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class PosExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('euros', [$this, 'getEuros']),
            new TwigFilter('cents', [$this, 'getCents']),
        ];
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('euros', [$this, 'getEuros']),
            new TwigFunction('cents', [$this, 'getCents']),
        ];
    }

    public function getEuros(float $price): string
    {
        return NumberUtility::getEurosFromPrice($price);
    }

    public function getCents(float $price): string
    {
        return NumberUtility::getCentsFromPrice($price);
    }
}
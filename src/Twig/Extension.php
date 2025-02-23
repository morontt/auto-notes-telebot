<?php

/**
 * User: morontt
 * Date: 23.02.2025
 * Time: 18:21
 */

namespace TeleBot\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class Extension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('footer_date', [$this, 'footerDate']),
        ];
    }

    public function footerDate(): string
    {
        $year = date('Y');
        if ($year === '2025') {
            return '2025';
        }

        return '2025-' . $year;
    }
}

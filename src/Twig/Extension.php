<?php declare(strict_types=1);

/**
 * User: morontt
 * Date: 23.02.2025
 * Time: 18:21
 */

namespace TeleBot\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class Extension extends AbstractExtension
{
    /**
     * Source: https://www.compart.com/en/unicode/category/Sc
     *
     * @var array<string, int>
     */
    private static array $currencyToCodePoints = [
        'EUR' => 0x20AC,
        'GEL' => 0x20BE,
        'RUB' => 0x20BD,
        'UAH' => 0x20B4,
        'USD' => 0x24,
    ];

    /**
     * @var array<string, string>
     */
    private static array $currencyToString = [
        'PLN' => 'zł',
    ];

    public function getFunctions(): array
    {
        return [
            new TwigFunction('footer_date', [self::class, 'footerDate']),
        ];
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('currency_symbol', [self::class, 'currencySymbol']),
        ];
    }

    public static function footerDate(): string
    {
        $year = date('Y');
        if ($year === '2025') {
            return '2025';
        }

        return '2025-' . $year;
    }

    public static function currencySymbol(string $code): string
    {
        if (!empty(self::$currencyToCodePoints[$code])) {
            return mb_chr(self::$currencyToCodePoints[$code]);
        }

        if (!empty(self::$currencyToString[$code])) {
            return self::$currencyToString[$code];
        }

        return $code;
    }
}

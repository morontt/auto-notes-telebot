<?php declare(strict_types=1);

/**
 * User: morontt
 * Date: 24.09.2025
 * Time: 09:09
 */

namespace TeleBot\Twig;

use TeleBot\DTO\List\PaginationMeta;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class Pagination extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('pagination', [$this, 'pagination'], ['is_safe' => ['html'], 'needs_environment' => true]),
        ];
    }

    public function pagination(Environment $env, PaginationMeta $meta, string $routeName): string
    {
        return $env->render('pagination/sliding.html.twig', [
            'pageCount' => $meta->getLast(),
            'current' => $meta->getCurrent(),
            'routeName' => $routeName,
            'pagesInRange' => range(1, $meta->getLast()),
        ]);
    }
}

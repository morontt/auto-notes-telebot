<?php declare(strict_types=1);
/**
 * User: morontt
 * Date: 01.05.2026
 * Time: 18:31
 */

namespace TeleBot\Controller;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

abstract class RecordController extends BaseController
{
    /**
     * @param array<string, mixed> $data
     *
     * @return array<string, mixed>
     */
    abstract protected function getFilterData(array $data): array;

    /**
     * @return array<string, mixed>
     */
    protected function handleFilterForm(FormInterface $form, Request $request): array
    {
        $data = [];
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = array_merge($data, $this->getFilterData($form->getData()));
        }

        return $data;
    }
}

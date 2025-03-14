<?php

/**
 * User: morontt
 * Date: 14.03.2025
 * Time: 22:24
 */

namespace TeleBot\Form\ChoiceList;

use Symfony\Component\Form\ChoiceList\Loader\AbstractChoiceLoader;

class RpcRepositoryChoiceLoader extends AbstractChoiceLoader
{
    /**
     * @var callable
     */
    private $callback;

    public function __construct(callable $callback, private $repo, private $user)
    {
        $this->callback = $callback;
    }

    protected function loadChoices(): iterable
    {
        return ($this->callback)($this->repo, $this->user);
    }
}

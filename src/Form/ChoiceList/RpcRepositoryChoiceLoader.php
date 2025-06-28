<?php

/**
 * User: morontt
 * Date: 14.03.2025
 * Time: 22:24
 */

namespace TeleBot\Form\ChoiceList;

use Symfony\Component\Form\ChoiceList\Loader\AbstractChoiceLoader;
use TeleBot\Security\AccessTokenAwareInterface;
use TeleBot\Service\RPC\AbstractRepository as AbstractRpcRepository;

class RpcRepositoryChoiceLoader extends AbstractChoiceLoader
{
    /**
     * @var callable
     */
    private $callback;

    public function __construct(
        callable $callback,
        private AbstractRpcRepository $repo,
        private AccessTokenAwareInterface $user,
    ) {
        $this->callback = $callback;
    }

    /**
     * @return array<mixed>
     */
    protected function loadChoices(): iterable
    {
        return ($this->callback)($this->repo, $this->user);
    }
}

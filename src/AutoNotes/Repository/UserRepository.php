<?php declare(strict_types=1);
/**
 * User: morontt
 * Date: 09.05.2026
 * Time: 11:12
 */

namespace TeleBot\AutoNotes\Repository;

use Doctrine\ORM\EntityRepository;
use TeleBot\AutoNotes\Entity\User;

/**
 * @extends EntityRepository<User>
 */
class UserRepository extends EntityRepository
{
}

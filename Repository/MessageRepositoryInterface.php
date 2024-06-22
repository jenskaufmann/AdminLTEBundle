<?php

/*
 * This file is part of the AdminLTE bundle.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ibisit\AdminLTEBundle\Repository;

use Ibisit\AdminLTEBundle\Model\MessageInterface;

interface MessageRepositoryInterface
{
    /**
     * @return int
     */
    public function getTotal();

    /**
     * @return iterable<MessageInterface>
     */
    public function getMessages();
}

<?php

/*
 * This file is part of the AdminLTE bundle.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ibisit\AdminLTEBundle\Repository;

use ibisit\AdminLTEBundle\Model\NotificationInterface;

interface NotificationRepositoryInterface
{
    /**
     * @return int
     */
    public function getTotal();

    /**
     * @return iterable<NotificationInterface>
     */
    public function getNotifications();
}

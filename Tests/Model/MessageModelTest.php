<?php

/*
 * This file is part of the AdminLTE bundle.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ibisit\AdminLTEBundle\Tests\Model;

use ibisit\AdminLTEBundle\Model\MessageModel;
use ibisit\AdminLTEBundle\Model\UserModel;
use PHPUnit\Framework\TestCase;

class MessageModelTest extends TestCase
{
    public function testGetIdentifier()
    {
        $sut = new MessageModel(new UserModel(), 'foo');
        $this->assertEquals('foo', $sut->getIdentifier());
        $sut->setId('42');
        $this->assertEquals('42', $sut->getIdentifier());
    }
}

<?php

/*
 * This file is part of the AdminLTE bundle.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ibisit\AdminLTEBundle\Twig;

use Ibisit\AdminLTEBundle\Event\BreadcrumbMenuEvent;
use Ibisit\AdminLTEBundle\Event\MessageListEvent;
use Ibisit\AdminLTEBundle\Event\NavbarUserEvent;
use Ibisit\AdminLTEBundle\Event\NotificationListEvent;
use Ibisit\AdminLTEBundle\Event\ShowUserEvent;
use Ibisit\AdminLTEBundle\Event\SidebarMenuEvent;
use Ibisit\AdminLTEBundle\Event\SidebarUserEvent;
use Ibisit\AdminLTEBundle\Event\TaskListEvent;
use Ibisit\AdminLTEBundle\Helper\ContextHelper;
use Ibisit\AdminLTEBundle\Model\MenuItemInterface;
use Ibisit\AdminLTEBundle\Model\UserDetailsInterface;
use Ibisit\AdminLTEBundle\Model\UserInterface;
use Ibisit\AdminLTEBundle\Repository\MessageRepositoryInterface;
use Ibisit\AdminLTEBundle\Repository\NotificationRepositoryInterface;
use Ibisit\AdminLTEBundle\Repository\TaskRepositoryInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Twig\Extension\RuntimeExtensionInterface;

final class EventsExtension implements RuntimeExtensionInterface
{
    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;
    /**
     * @var ContextHelper
     */
    private $helper;

    public function __construct(EventDispatcherInterface $dispatcher, ContextHelper $helper)
    {
        $this->eventDispatcher = $dispatcher;
        $this->helper = $helper;
    }

    /**
     * @param Request $request
     * @return MenuItemInterface[]
     */
    public function getMenu(Request $request): ?array
    {
        if (!$this->eventDispatcher->hasListeners(SidebarMenuEvent::class)) {
            return null;
        }

        /** @var SidebarMenuEvent $event */
        $event = $this->eventDispatcher->dispatch(new SidebarMenuEvent($request));

        return $event->getItems();
    }

    public function getSidebarUser(): ?UserInterface
    {
        if (!$this->eventDispatcher->hasListeners(SidebarUserEvent::class)) {
            return null;
        }

        /** @var SidebarUserEvent $event */
        $event = $this->eventDispatcher->dispatch(new SidebarUserEvent());

        return $event->getUser();
    }

    public function getBreadcrumbs(Request $request): ?array
    {
        if (!$this->eventDispatcher->hasListeners(BreadcrumbMenuEvent::class)) {
            return null;
        }

        /** @var BreadcrumbMenuEvent $event */
        $event = $this->eventDispatcher->dispatch(new BreadcrumbMenuEvent($request));

        /** @var MenuItemInterface $active */
        $active = $event->getActive();
        $list = [];
        if (null !== $active) {
            $list[] = $active;
            while (null !== ($item = $active->getActiveChild())) {
                $list[] = $item;
                $active = $item;
            }
        }

        return $list;
    }

    public function getNotifications(?int $max = null): ?NotificationRepositoryInterface
    {
        if (!$this->eventDispatcher->hasListeners(NotificationListEvent::class)) {
            return null;
        }

        if (null === $max) {
            $max = (int) $this->helper->getOption('max_navbar_notifications');
        }

        /** @var NotificationListEvent $listEvent */
        $listEvent = $this->eventDispatcher->dispatch(new NotificationListEvent($max));

        return $listEvent;
    }

    public function getMessages(?int $max = null): ?MessageRepositoryInterface
    {
        if (!$this->eventDispatcher->hasListeners(MessageListEvent::class)) {
            return null;
        }

        if (null === $max) {
            $max = (int) $this->helper->getOption('max_navbar_messages');
        }

        /** @var MessageListEvent $listEvent */
        $listEvent = $this->eventDispatcher->dispatch(new MessageListEvent($max));

        return $listEvent;
    }

    public function getTasks(?int $max = null): ?TaskRepositoryInterface
    {
        if (!$this->eventDispatcher->hasListeners(TaskListEvent::class)) {
            return null;
        }

        if (null === $max) {
            $max = (int) $this->helper->getOption('max_navbar_tasks');
        }

        /** @var TaskListEvent $listEvent */
        $listEvent = $this->eventDispatcher->dispatch(new TaskListEvent($max));

        return $listEvent;
    }

    public function getUserDetails(): ?UserDetailsInterface
    {
        if (!$this->eventDispatcher->hasListeners(NavbarUserEvent::class)) {
            return null;
        }

        /** @var ShowUserEvent $userEvent */
        $userEvent = $this->eventDispatcher->dispatch(new NavbarUserEvent());

        if ($userEvent instanceof ShowUserEvent && null !== $userEvent->getUser()) {
            return $userEvent;
        }

        return null;
    }
}

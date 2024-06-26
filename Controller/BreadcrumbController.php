<?php

/*
 * This file is part of the AdminLTE bundle.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ibisit\AdminLTEBundle\Controller;

use ibisit\AdminLTEBundle\Event\BreadcrumbMenuEvent;
use ibisit\AdminLTEBundle\Event\SidebarMenuEvent;
use ibisit\AdminLTEBundle\Model\MenuItemInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Controller to handle breadcrumb display inside the layout
 */
class BreadcrumbController extends EmitterController
{
    /**
     * Controller Reference action to be called inside the layout.
     *
     * Triggers the {@link BreadcrumbMenuEvent} to receive the currently active menu chain.
     *
     * If there are no listeners attached for this event, the return value is an empty response.
     *
     * @param Request $request
     * @return Response
     */
    public function breadcrumbAction(Request $request): Response
    {
        @trigger_error('BreadcrumbController::breadcrumbAction() is deprecated and will be removed with 4.0', E_USER_DEPRECATED);

        if (!$this->hasListener(BreadcrumbMenuEvent::class)) {
            return new Response();
        }

        /** @var SidebarMenuEvent $event */
        $event = $this->dispatch(new BreadcrumbMenuEvent($request));

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

        return $this->render('@AdminLTE/Breadcrumb/breadcrumb.html.twig', [
            'active' => $list,
        ]);
    }
}

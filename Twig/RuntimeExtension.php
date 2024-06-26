<?php

/*
 * This file is part of the AdminLTE bundle.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ibisit\AdminLTEBundle\Twig;

use ibisit\AdminLTEBundle\Helper\Constants;
use ibisit\AdminLTEBundle\Helper\ContextHelper;
use Twig\Extension\RuntimeExtensionInterface;

final class RuntimeExtension implements RuntimeExtensionInterface
{
    /**
     * @var ContextHelper<string, mixed>
     */
    private $context;
    /**
     * @var array<string, string|null>
     */
    private $routes;

    /**
     * @param ContextHelper<string, mixed> $contextHelper
     * @param array<string, string|null> $routes
     */
    public function __construct(ContextHelper $contextHelper, array $routes)
    {
        $this->context = $contextHelper;
        $this->routes = $routes;
    }

    /**
     * @param string $routeName
     * @return string
     */
    public function getRouteByAlias($routeName)
    {
        return $this->routes[$routeName] ?? $routeName;
    }

    /**
     * @param string $type
     * @return string
     */
    public function getTextType($type)
    {
        switch ($type) {
            case Constants::TYPE_INFO:
                $type = Constants::COLOR_AQUA;
                break;
            case Constants::TYPE_WARNING:
                $type = Constants::COLOR_YELLOW;
                break;
            case Constants::TYPE_SUCCESS:
                $type = Constants::COLOR_GREEN;
                break;
            case Constants::TYPE_ERROR:
                $type = Constants::COLOR_RED;
                break;
        }

        return 'text-' . $type;
    }

    /**
     * @param string $classes
     * @return string
     */
    public function bodyClass($classes = '')
    {
        $classList = [$classes];
        $options = $this->context->getOptions();

        if (isset($options['skin'])) {
            $classList[] = $options['skin'];
        }
        if (isset($options['fixed_layout']) && true == $options['fixed_layout']) {
            $classList[] = 'fixed';
        }
        if (isset($options['boxed_layout']) && true == $options['boxed_layout']) {
            $classList[] = 'layout-boxed';
        }
        if (isset($options['collapsed_sidebar']) && true == $options['collapsed_sidebar']) {
            $classList[] = 'sidebar-collapse';
        }
        if (isset($options['mini_sidebar']) && true == $options['mini_sidebar']) {
            $classList[] = 'sidebar-mini';
        }

        return implode(' ', array_values($classList));
    }
}

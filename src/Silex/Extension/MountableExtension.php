<?php

/*
 * This file is part of the Silex framework.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Silex\Extension;

use Silex\Application;
use Silex\ExtensionInterface;
use Symfony\Component\HttpFoundation\SessionStorage\NativeSessionStorage;
use Symfony\Component\HttpFoundation\Session;
use Symfony\Component\HttpKernel\Events as HttpKernelEvents;

class MountableExtension implements ExtensionInterface
{
    private $app;

    public function register(Application $app)
    {
        $this->app = $app;

        $app['mountable'] = $this;
    }

    /**
     * Embeds an application into another one under the given prefix
     *
     * @param string      $prefix A prefix (like /foo -- no / at the end)
     * @param Application $app An application to embed
     */
    public function mount($prefix, Application $app)
    {
        foreach ($app['controllers']->all() as $controller) {
            $controller->getRoute()->setPattern(rtrim($prefix, '/').$controller->getRoute()->getPattern());

            $this->app['controllers']->add($controller);
        }
    }
}

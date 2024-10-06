<?php

namespace Webkul\Ronanbriot\CoreFrameworkBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Webkul\Ronanbriot\CoreFrameworkBundle\DependencyInjection\Passes;
use Webkul\Ronanbriot\CoreFrameworkBundle\DependencyInjection\CoreFramework;

class UVDeskCoreFrameworkBundle extends Bundle
{
    /**
     * @inheritDoc
     */
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }

    public function getContainerExtension()
    {
        return new CoreFramework();
    }

    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container
            ->addCompilerPass(new Passes\Events())
            ->addCompilerPass(new Passes\Routes())
            ->addCompilerPass(new Passes\Extendables())
            ->addCompilerPass(new Passes\DashboardComponents())
            ->addCompilerPass(new Passes\Ticket\Widgets())
            ->addCompilerPass(new Passes\Ticket\QuickActionButtons());
    }
}

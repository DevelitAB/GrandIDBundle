<?php

namespace Bsadnu\GrandIDBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class GrandIDBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        //$container->addCompilerPass(new RegisterMenusPass());
    }
}
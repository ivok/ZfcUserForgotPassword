<?php

namespace ZfcUserForgotPassword\Options;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use BeaucalUtil\ArrayLookup;

class ModuleFactory implements FactoryInterface {

    public function __invoke(
    ContainerInterface $container, $requestedName, array $options = null
    ) {
        $config = new ArrayLookup($container->get('Config'));
        return new Module($config->get(['zfcuserforgotpassword', 'module'], []));
    }

}

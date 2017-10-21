<?php

namespace ZfcUserForgotPassword\Service;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Doctrine\ORM\EntityManager;
use ZfcUserForgotPassword\Nonce\Generator;
use ZfcUserForgotPassword\Entity\Reset;

class ForgotPasswordFactory implements FactoryInterface {

    public function __invoke(
    ContainerInterface $container, $requestedName, array $options = null
    ) {
        $reset = new Reset;
        $reset->setNonceGenerator(new Generator);

        return new ForgotPassword(
        $container->get('zfcuser_module_options'),
        $container->get(EntityManager::class)->getRepository(Reset::class),
        $reset
        );
    }

}

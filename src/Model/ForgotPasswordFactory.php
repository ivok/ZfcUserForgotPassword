<?php

namespace ZfcUserForgotPassword\Model;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\InputFilter\InputFilterPluginManager;

class ForgotPasswordFactory implements FactoryInterface {

    public function __invoke(
    ContainerInterface $container, $requestedName, array $options = null
    ) {
        $model = new ForgotPassword;
        $model->setInputFilter(
        $container->get(InputFilterPluginManager::class)->get('InputFilter')
        );
        $model->init();
        return $model;
    }

}

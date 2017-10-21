<?php

namespace ZfcUserForgotPassword\Form;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use ZfcUserForgotPassword\Model\ForgotPassword as ForgotPasswordModel;

class ForgotPasswordFactory implements FactoryInterface {

    public function __invoke(
    ContainerInterface $container, $requestedName, array $options = null
    ) {
        $form = new ForgotPassword;
        $form->bind($container->get(ForgotPasswordModel::class));
        return $form;
    }

}

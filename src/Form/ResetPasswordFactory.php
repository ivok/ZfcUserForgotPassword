<?php

namespace ZfcUserForgotPassword\Form;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use ZfcUserForgotPassword\Model\ResetPassword as ResetPasswordModel;

class ResetPasswordFactory implements FactoryInterface {

    public function __invoke(
    ContainerInterface $container, $requestedName, array $options = null
    ) {
        $form = new ResetPassword;
        $form->bind($container->get(ResetPasswordModel::class));
        return $form;
    }

}

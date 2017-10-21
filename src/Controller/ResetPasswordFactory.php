<?php

namespace ZfcUserForgotPassword\Controller;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use ZfcUserForgotPassword\Form\ResetPassword as ResetPasswordForm;
use ZfcUserForgotPassword\Service\ForgotPassword as ForgotPasswordService;

class ResetPasswordFactory implements FactoryInterface {

    public function __invoke(
    ContainerInterface $container, $requestedName, array $options = null
    ) {
        return new ResetPassword(
        $container->get('zfcuser_user_mapper'),
        $container->get(ForgotPasswordService::class),
        $container->get(ResetPasswordForm::class)
        );
    }

}

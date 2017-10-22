<?php

namespace ZfcUserForgotPassword\Controller;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use ZfcUserForgotPassword\Form\ForgotPassword as ForgotPasswordForm;
use ZfcUserForgotPassword\Service\ForgotPassword as ForgotPasswordService;

class ForgotPasswordFactory implements FactoryInterface {

    public function __invoke(
    ContainerInterface $container, $requestedName, array $options = null
    ) {
        return new ForgotPassword(
        $container->get('zfcuser_user_mapper'),
        $container->get(ForgotPasswordService::class),
        $container->get(ForgotPasswordForm::class),
        $container->get('zfcuserforgotpassword_sender')
        );
    }

}

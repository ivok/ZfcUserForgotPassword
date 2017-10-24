<?php

namespace ZfcUserForgotPasswordTest\Form;

use ZfcUserForgotPassword\Model\ResetPassword as ResetPasswordModel;
use ZfcUserForgotPassword\Form\ResetPassword as ResetPasswordForm;
use ZfcUserForgotPassword\Form\ResetPasswordFactory;
use Interop\Container\ContainerInterface;
use Zend\Form\Element;

class ResetPasswordTest extends \PHPUnit_Framework_TestCase {

    public function testFactory() {
        $model = new ResetPasswordModel;
        $container = $this->getMock(ContainerInterface::class);
        $container->expects($this->any())->method('get')->willReturn($model);

        $factory = new ResetPasswordFactory;
        $form = $factory($container, ResetPasswordForm::class);

        $this->assertInstanceOf(Element::class, $form->get('password'));
        $this->assertInstanceOf(Element::class, $form->get('passwordConfirm'));
        $this->assertInstanceOf(Element::class, $form->get('submit'));
    }

}

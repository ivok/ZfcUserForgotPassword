<?php

namespace ZfcUserForgotPasswordTest\Form;

use ZfcUserForgotPassword\Model\ForgotPassword as ForgotPasswordModel;
use ZfcUserForgotPassword\Form\ForgotPassword as ForgotPasswordForm;
use ZfcUserForgotPassword\Form\ForgotPasswordFactory;
use Interop\Container\ContainerInterface;
use Zend\Form\Element;

class ForgotPasswordTest extends \PHPUnit_Framework_TestCase {

    public function testFactory() {
        $model = new ForgotPasswordModel;
        $container = $this->getMock(ContainerInterface::class);
        $container->expects($this->any())->method('get')->willReturn($model);

        $factory = new ForgotPasswordFactory;
        $form = $factory($container, ForgotPasswordForm::class);

        $this->assertInstanceOf(Element::class, $form->get('email'));
        $this->assertInstanceOf(Element::class, $form->get('submit'));
    }

}

<?php

namespace ZfcUserForgotPasswordTest\Model;

use ZfcUserForgotPassword\Model\ForgotPassword;
use ZfcUserForgotPassword\Model\ForgotPasswordFactory;
use Interop\Container\ContainerInterface;
use Faker\Factory as FakerFactory;
use Zend\InputFilter\InputFilterPluginManager;
use Zend\InputFilter\InputFilter;

class ForgotPasswordTest extends \PHPUnit_Framework_TestCase {

    public function testFactory() {
        $inputFilter = new InputFilter;
        $manager = $this->getMock(
        InputFilterPluginManager::class, [], [], '', false
        );
        $manager->expects($this->any())->method('get')->willReturn($inputFilter);

        $container = $this->getMock(ContainerInterface::class);
        $container->expects($this->any())->method('get')->willReturn($manager);

        $factory = new ForgotPasswordFactory;
        $model = $factory($container, ForgotPassword::class);
        $model->init();

        $faker = FakerFactory::create();
        $this->assertNull($model->getEmail());
        $email = $faker->safeEmail;
        $model->setEmail($email);
        $this->assertSame($email, $model->getEmail());

        $this->assertSame(
        ['email' => $model->getEmail()], $model->getArrayCopy()
        );

        $newData = ['email' => $faker->safeEmail];
        $model->exchangeArray($newData);
        $this->assertEquals($newData['email'], $model->getEmail());
        $this->assertSame($newData, $model->getArrayCopy());
    }

}

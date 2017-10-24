<?php

namespace ZfcUserForgotPasswordTest\Model;

use ZfcUserForgotPassword\Model\ResetPassword;
use ZfcUserForgotPassword\Model\ResetPasswordFactory;
use Interop\Container\ContainerInterface;
use Zend\InputFilter\InputFilterPluginManager;
use Zend\InputFilter\InputFilter;

class ResetPasswordTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var ResetPassword
     */
    protected $model;

    public function setUp() {
        parent::setUp();

        $inputFilter = new InputFilter;
        $manager = $this->getMock(
        InputFilterPluginManager::class, [], [], '', false
        );
        $manager->expects($this->any())->method('get')->willReturn($inputFilter);

        $container = $this->getMock(ContainerInterface::class);
        $container->expects($this->any())->method('get')->willReturn($manager);

        $factory = new ResetPasswordFactory;
        $this->model = $factory($container, ResetPassword::class);
        $this->model->init();
    }

    /**
     * @dataProvider dataSet
     */
    public function testSetPassword($password, $passwordConfirm) {
        $this->assertNull($this->model->getPassword());
        $this->assertNull($this->model->getPasswordConfirm());

        $this->model->setPassword($password);
        $this->assertSame($password, $this->model->getPassword());

        $this->model->setPasswordConfirm($passwordConfirm);
        $this->assertSame($passwordConfirm, $this->model->getPasswordConfirm());

        $this->assertSame(
        ['password' => $password, 'passwordConfirm' => $passwordConfirm],
        $this->model->getArrayCopy()
        );

        $newData = ['password' => $password, 'passwordConfirm' => $password];
        $this->model->exchangeArray($newData);
        $this->assertEquals($newData['password'], $this->model->getPassword());
        $this->assertEquals($newData['passwordConfirm'],
        $this->model->getPasswordConfirm());
        $this->assertSame($newData, $this->model->getArrayCopy());
    }

    /**
     * @dataProvider dataSet
     */
    public function testExchangeArray($password, $passwordConfirm) {
        $this->assertSame(
        ['password' => null, 'passwordConfirm' => null],
        $this->model->getArrayCopy()
        );

        $data = ['password' => $password, 'passwordConfirm' => $passwordConfirm];
        $this->model->exchangeArray($data);
        $this->assertSame($data, $this->model->getArrayCopy());
        $this->assertSame($password, $this->model->getPassword());
        $this->assertSame($passwordConfirm, $this->model->getPasswordConfirm());
    }

    public static function dataSet() {
        return [
            ['', ''],
            ['asdf', 'qwer'],
            ['1234567890', '1234567890'],
        ];
    }

}

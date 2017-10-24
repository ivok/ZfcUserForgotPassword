<?php

namespace ZfcUserForgotPasswordTest\Controller;

use ZfcUserForgotPassword\Controller\ResetPassword as ResetPasswordController;
use ZfcUserForgotPassword\Controller\Plugin\Login;
use ZfcUserForgotPassword\Options\Module as ModuleOptions;
use ZfcUserForgotPassword\Service\ForgotPassword as ForgotPasswordService;
use ZfcUserForgotPassword\Form\ResetPassword as ResetPasswordForm;
use ZfcUserForgotPassword\Model\ResetPassword as ResetPasswordModel;
use ZfcUserForgotPassword\Entity\Reset;
use ZfcUser\Mapper\User as UserMapper;
use ZfcUser\Entity\User;
use Faker\Factory as FakerFactory;
use Zend\Mvc\Plugin\FlashMessenger\FlashMessenger;
use Zend\Mvc\Controller\Plugin\Redirect;
use Zend\Mvc\Controller\Plugin\Params;

class ResetPasswordTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var ResetPasswordController
     */
    protected $controller;

    protected function setUp() {
        parent::setUp();

        $userMapper = $this->getMock(UserMapper::class);
        $forgotPasswordService = $this->getMock(
        ForgotPasswordService::class, [], [], '', false
        );
        $form = $this->getMock(ResetPasswordForm::class);
        $form->expects($this->any())
        ->method('getMessages')->willReturn(['error message']);
        $this->controller = new ResetPasswordController(
        new ModuleOptions, $userMapper, $forgotPasswordService, $form
        );

        $this->controller->getPluginManager()->setService(
        'flashMessenger', $this->getMock(FlashMessenger::class)
        );

        $request = $this->controller->getRequest();
        $request->setMethod('POST');

        $params = $this->getMock(Params::class);
        $this->controller->getPluginManager()->setService(
        'params', $params
        );

        $this->controller->getPluginManager()->setService(
        'zufpLogin', $this->getMock(Login::class)
        );

        $this->controller->getPluginManager()->setAllowOverride(true);
    }

    public function testIndexActionGet() {
        $request = $this->controller->getRequest();
        $request->setMethod('GET');

        $view = $this->controller->indexAction();
        $this->assertCount(1, $view->getVariables());
        $this->assertInstanceOf(ResetPasswordForm::class, $view->form);
    }

    public function testIndexActionPostInvalid() {
        $this->controller->getPluginManager()
        ->get('flashMessenger')->expects($this->once())
        ->method('addMessage')->with('error message');

        $view = $this->controller->indexAction();

        $this->assertCount(1, $view->getVariables());
        $this->assertInstanceOf(ResetPasswordForm::class, $view->form);
    }

    public function testIndexActionValidButNoUser() {
        $model = new ResetPasswordModel;

        $form = $this->controller->getForm();
        $form->expects($this->any())->method('isValid')->willReturn(true);
        $form->expects($this->any())->method('getData')->willReturn($model);

        $this->controller->getPluginManager()
        ->get('flashMessenger')->expects($this->once())
        ->method('addMessage')->with('Could not reset your password');

        $this->controller->getPluginManager()
        ->get('params')->expects($this->once())
        ->method('__invoke')->with('user');

        $view = $this->controller->indexAction();
        $this->assertCount(1, $view->getVariables());
        $this->assertInstanceOf(ResetPasswordForm::class, $view->form);
    }

    public function testIndexActionNoReset() {
        $faker = FakerFactory::create();

        $user = new User;
        $user->setId($faker->randomNumber(6));
        $user->setEmail($faker->safeEmail);
        $userMapper = $this->controller->getUserMapper();
        $userMapper->expects($this->once())->method('findById')->willReturn($user);

        $this->controller->getPluginManager()
        ->get('flashMessenger')->expects($this->once())
        ->method('addMessage')->with('Could not reset your password');

        $model = new ResetPasswordModel;
        $model->setPassword($faker->randomNumber(6));

        $form = $this->controller->getForm();
        $form->expects($this->any())->method('isValid')->willReturn(true);
        $form->expects($this->any())->method('getData')->willReturn($model);

        $view = $this->controller->indexAction();
        $this->assertCount(1, $view->getVariables());
        $this->assertInstanceOf(ResetPasswordForm::class, $view->form);
    }

    /**
     * @dataProvider dataIndexActionValidSuccess
     */
    public function testIndexActionValidSuccess($isAutoLogin) {
        $faker = FakerFactory::create();

        $user = new User;
        $user->setId($faker->randomNumber(6));
        $user->setEmail($faker->safeEmail);
        $userMapper = $this->controller->getUserMapper();
        $userMapper->expects($this->once())->method('findById')->willReturn($user);

        $flashMessenger = $this->controller->getPluginManager()->get('flashMessenger');
        $flashMessenger->method('setNamespace')->willReturn($flashMessenger);

        $reset = new Reset;
        $forgotPasswordService = $this->controller->getForgotPasswordService();

        $forgotPasswordService->expects($this->once())
        ->method('resetPassword')->with($user)->willReturn($reset);

        $model = new ResetPasswordModel;
        $model->setPassword($faker->randomNumber(6));

        $form = $this->controller->getForm();
        $form->expects($this->any())->method('isValid')->willReturn(true);
        $form->expects($this->any())->method('getData')->willReturn($model);

        $pluginManager = $this->controller->getPluginManager();
        $options = $this->controller->getModuleOptions();
        $options->setAutoLogin($isAutoLogin);
        if ($isAutoLogin) {
            $login = $pluginManager->get('zufpLogin');
            $login->expects($this->once())->method('__invoke')
            ->with($user->getEmail(), $model->getPassword())->willReturn('zufpLogin return');

            $this->assertEquals('zufpLogin return',
            $this->controller->indexAction());
            return;
        }

        $redirect = $this->getMock(Redirect::class);
        $redirect->expects($this->once())->method('toRoute')
        ->with('zfcuser/reset_password/success')->willReturn('redirect return');
        $pluginManager->setService('redirect', $redirect);

        $this->assertEquals('redirect return', $this->controller->indexAction());
    }

    public static function dataIndexActionValidSuccess() {
        return [
            [true],
            [false],
        ];
    }

    public function testSuccessAction() {
        $this->controller->successAction(); // doesn't do anything
    }

}

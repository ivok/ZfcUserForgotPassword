<?php

namespace ZfcUserForgotPasswordTest\Controller;

use ZfcUserForgotPassword\Controller\ForgotPassword as ForgotPasswordController;
use ZfcUserForgotPassword\Service\ForgotPassword as ForgotPasswordService;
use ZfcUserForgotPassword\Form\ForgotPassword as ForgotPasswordForm;
use ZfcUserForgotPassword\Model\ForgotPassword as ForgotPasswordModel;
use ZfcUserForgotPassword\Sender\Blackhole as Sender;
use ZfcUserForgotPassword\Entity\Reset;
use ZfcUser\Mapper\User as UserMapper;
use ZfcUser\Entity\User;
use Faker\Factory as FakerFactory;
use Zend\Mvc\Plugin\FlashMessenger\FlashMessenger;
use Zend\Mvc\Controller\Plugin\Redirect;

class ForgotPasswordTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var ForgotPasswordController
     */
    protected $controller;

    protected function setUp() {
        parent::setUp();

        $userMapper = $this->getMock(UserMapper::class);
        $forgotPasswordService = $this->getMock(
        ForgotPasswordService::class, [], [], '', false
        );
        $form = $this->getMock(ForgotPasswordForm::class);
        $form->expects($this->any())
        ->method('getMessages')->willReturn(['error message']);
        $sender = $this->getMock(Sender::class);
        $this->controller = new ForgotPasswordController(
        $userMapper, $forgotPasswordService, $form, $sender
        );

        $this->controller->getPluginManager()->setService(
        'flashMessenger', $this->getMock(FlashMessenger::class)
        );

        $request = $this->controller->getRequest();
        $request->setMethod('POST');
    }

    public function testIndexActionGet() {
        $request = $this->controller->getRequest();
        $request->setMethod('GET');

        $view = $this->controller->indexAction();
        $this->assertCount(1, $view->getVariables());
        $this->assertInstanceOf(ForgotPasswordForm::class, $view->form);
    }

    public function testIndexActionPostInvalid() {
        $request = $this->controller->getRequest();

        $this->controller->getPluginManager()
        ->get('flashMessenger')->expects($this->once())
        ->method('addMessage')->with('error message');

        $view = $this->controller->indexAction();

        $this->assertCount(1, $view->getVariables());
        $this->assertInstanceOf(ForgotPasswordForm::class, $view->form);
    }

    public function testIndexActionValidButNoUser() {
        $model = new ForgotPasswordModel;

        $form = $this->controller->getForm();
        $form->expects($this->any())->method('isValid')->willReturn(true);
        $form->expects($this->any())->method('getData')->willReturn($model);

        $this->controller->getPluginManager()
        ->get('flashMessenger')->expects($this->once())
        ->method('addMessage')->with('That user was not found');

        $view = $this->controller->indexAction();
        $this->assertCount(1, $view->getVariables());
        $this->assertInstanceOf(ForgotPasswordForm::class, $view->form);
    }

    public function testIndexActionValidSuccess() {
        $faker = FakerFactory::create();

        $user = new User;
        $user->setId($faker->randomNumber(6));
        $user->setEmail($faker->safeEmail);
        $userMapper = $this->controller->getUserMapper();
        $userMapper->expects($this->once())->method('findByEmail')->willReturn($user);

        $reset = new Reset;
        $forgotPasswordService = $this->controller->getForgotPasswordService();
        $forgotPasswordService->expects($this->once())
        ->method('createReset')->with($user->getId())->willReturn($reset);

        $redirect = $this->getMock(Redirect::class);
        $redirect->expects($this->once())
        ->method('toRoute')->with('zfcuser/forgot_password/success');
        $pluginManager = $this->controller->getPluginManager();
        $pluginManager->setAllowOverride(true);
        $pluginManager->setService('redirect', $redirect);

        $request = $this->controller->getRequest();
        $request->setMethod('POST');

        $model = new ForgotPasswordModel;

        $form = $this->controller->getForm();
        $form->expects($this->any())->method('isValid')->willReturn(true);
        $form->expects($this->any())->method('getData')->willReturn($model);

        $this->controller->indexAction();
    }

    public function testSuccessAction() {
        $this->controller->successAction(); // doesn't do anything
    }

}

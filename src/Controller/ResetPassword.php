<?php

namespace ZfcUserForgotPassword\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Form\Form;
use ZfcUser\Mapper\User as UserMapper;
use ZfcUserForgotPassword\Options\Module as ModuleOptions;
use ZfcUserForgotPassword\Sender\SenderInterface;
use ZfcUserForgotPassword\Service\ForgotPassword as ForgotPasswordService;

class ResetPassword extends AbstractActionController {

    /**
     * @var ModuleOptions
     */
    protected $moduleOptions;

    /**
     * @var UserMapper
     */
    protected $userMapper;

    /**
     * @var ForgotPasswordService
     */
    protected $forgotPasswordService;

    /**
     * @var Form
     */
    protected $form;

    /**
     * @var SenderInterface
     */
    protected $sender;

    public function __construct(
    ModuleOptions $moduleOptions, UserMapper $userMapper,
    ForgotPasswordService $forgotPasswordService, Form $form
    ) {
        $this->moduleOptions = $moduleOptions;
        $this->userMapper = $userMapper;
        $this->forgotPasswordService = $forgotPasswordService;
        $this->form = $form;
    }

    public function indexAction() {
        $view = new ViewModel([
            'form' => $this->form
        ]);
        if (!$this->getRequest()->isPost()) {
            return $view;
        }

        $this->form->setData($this->getRequest()->getPost());
        if (!$this->form->isValid()) {
            foreach ($this->form->getMessages() as $message) {
                $this->flashMessenger()->addMessage($message, null, 0);
            }
            return $view;
        }

        $model = $this->form->getData();
        $user = $this->userMapper->findById($this->params('user'));
        if (!$user) {
            $this->flashMessenger()->addMessage(
            'Could not reset your password', null, 0
            );
            return $view;
        }

        $success = $this->forgotPasswordService->resetPassword(
        $user, $this->params('nonce'), $model->getPassword()
        );
        if (!$success) {
            $this->flashMessenger()->addMessage(
            'Could not reset your password', null, 0
            );
            return $view;
        }

        $this->flashMessenger()->setNamespace('zfcuserforgotpassword')
        ->addMessage('Your password reset is successful.');

        if ($this->moduleOptions->getAutoLogin()) {
            return $this->zufpLogin($user->getEmail(), $model->getPassword());
        }
        return $this->redirect()->toRoute('zfcuser/reset_password/success');
    }

    public function successAction() {

    }

}

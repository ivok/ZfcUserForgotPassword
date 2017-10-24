<?php

namespace ZfcUserForgotPassword\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Form\Form;
use ZfcUser\Mapper\User as UserMapper;
use ZfcUserForgotPassword\Sender\SenderInterface;
use ZfcUserForgotPassword\Service\ForgotPassword as ForgotPasswordService;

class ForgotPassword extends AbstractActionController {

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
    UserMapper $userMapper, ForgotPasswordService $forgotPasswordService,
    Form $form, SenderInterface $sender
    ) {
        $this->userMapper = $userMapper;
        $this->forgotPasswordService = $forgotPasswordService;
        $this->form = $form;
        $this->sender = $sender;
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
        $user = $this->userMapper->findByEmail($model->getEmail());
        if (!$user) {
            $this->flashMessenger()->addMessage(
            'That user was not found', null, 0
            );
            return $view;
        }

        $reset = $this->forgotPasswordService->createReset($user->getId());
        $this->sender->send($model->getEmail(), $reset);
        return $this->redirect()->toRoute('zfcuser/forgot_password/success');
    }

    public function successAction() {

    }

    public function getForm() {
        return $this->form;
    }

    public function getUserMapper() {
        return $this->userMapper;
    }

    public function getForgotPasswordService() {
        return $this->forgotPasswordService;
    }

}

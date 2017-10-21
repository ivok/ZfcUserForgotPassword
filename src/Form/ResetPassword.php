<?php

namespace ZfcUserForgotPassword\Form;

use Zend\Form\Form;

class ResetPassword extends Form {

    public function __construct($name = null, $options = []) {
        parent::__construct($name, $options);
        $this->setName('reset-password');
        $this->setAttribute('method', 'post');

        $this->add([
            'name' => 'password',
            'options' => [
                'label' => 'New Password',
            ],
        ]);
        $this->add([
            'name' => 'passwordConfirm',
            'options' => [
                'label' => 'New Password (Confirm)',
            ],
        ]);
        $this->add([
            'name' => 'submit',
            'options' => [
                'label' => 'Reset Password',
            ],
            'attributes' => [
                'type' => 'submit',
                'value' => 'Reset Password',
            ],
        ]);
    }

}

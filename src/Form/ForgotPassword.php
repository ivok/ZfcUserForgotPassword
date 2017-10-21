<?php

namespace ZfcUserForgotPassword\Form;

use Zend\Form\Form;

class ForgotPassword extends Form {

    public function __construct($name = null, $options = []) {
        parent::__construct($name, $options);
        $this->setName('forgot-password');
        $this->setAttribute('method', 'post');

        $this->add([
            'name' => 'email',
            'options' => [
                'label' => 'Email',
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

<?php

namespace ZfcUserForgotPassword\Model;

use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterAwareTrait;
use Zend\Stdlib\Parameters;

class ResetPassword implements InputFilterAwareInterface {

    use InputFilterAwareTrait;

    /**
     * @var string
     */
    protected $password;

    /**
     * @var string
     */
    protected $passwordConfirm;

    public function init() {
        if ($this->getInputFilter()->count()) {
            return;
        }

        $factory = $this->inputFilter->getFactory();
        $this->inputFilter->add($factory->createInput([
            'name' => 'password',
            'required' => true,
            'allow_empty' => false,
            'filters' => [['name' => 'StringTrim']],
        ]));
        $this->inputFilter->add($factory->createInput([
            'name' => 'passwordConfirm',
            'required' => true,
            'allow_empty' => false,
            'validators' => [
                [
                    'name' => 'Identical',
                    'options' => ['token' => 'password']
                ],
            ],
            'filters' => [['name' => 'StringTrim']],
        ]));
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    public function getPassword() {
        return $this->password;
    }

    public function setPasswordConfirm($passwordConfirm) {
        $this->passwordConfirm = $passwordConfirm;
    }

    public function getPasswordConfirm() {
        return $this->passwordConfirm;
    }

    public function getArrayCopy() {
        return [
            'password' => $this->password,
            'passwordConfirm' => $this->passwordConfirm,
        ];
    }

    public function exchangeArray($data) {
        $params = new Parameters($data);
        $this->password = $params->password;
        $this->passwordConfirm = $params->passwordConfirm;
    }

}

<?php

namespace ZfcUserForgotPassword\Model;

use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterAwareTrait;
use Zend\Stdlib\Parameters;

class ForgotPassword implements InputFilterAwareInterface {

    use InputFilterAwareTrait;

    /**
     * @var string
     */
    protected $email;

    public function init() {
        if ($this->getInputFilter()->count()) {
            return;
        }

        $factory = $this->inputFilter->getFactory();
        $this->inputFilter->add($factory->createInput([
            'name' => 'email',
            'required' => true,
            'allow_empty' => false,
            'validators' => [['name' => 'EmailAddress']],
        ]));
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getArrayCopy() {
        return [
            'email' => $this->email
        ];
    }

    public function exchangeArray($data) {
        $params = new Parameters($data);
        $this->email = $params->email;
    }

}

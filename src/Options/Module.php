<?php

namespace ZfcUserForgotPassword\Options;

use Zend\Stdlib\AbstractOptions;

class Module extends AbstractOptions {

    protected $autoLogin = true;

    /**
     * @return bool
     */
    public function getAutoLogin() {
        return $this->autoLogin;
    }

    /**
     * @param bool $autoLogin
     * @return ForgotPassword
     */
    public function setAutoLogin($autoLogin) {
        $this->autoLogin = $autoLogin;
        return $this;
    }

}

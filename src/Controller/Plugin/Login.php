<?php

namespace ZfcUserForgotPassword\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;

class Login extends AbstractPlugin {

    const LOGIN_CONTROLLER = 'zfcuser';
    const LOGIN_ACTION = 'authenticate';

    /**
     * @param string $identity
     * @param string $credential
     * @return mixed
     */
    public function __invoke($identity, $credential) {
        $post = $this->getController()->getRequest()->getPost();
        $post->identity = $identity;
        $post->credential = $credential;
        return $this->getController()->forward()->dispatch(
        static::LOGIN_CONTROLLER, ['action' => static::LOGIN_ACTION]
        );
    }

}

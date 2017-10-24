<?php

namespace ZfcUserForgotPasswordTest\Controller\Plugin;

use ZfcUserForgotPassword\Controller\Plugin\Login;
use ZfcUserForgotPassword\Controller\ResetPassword;
use Zend\Http\Request;
use Zend\Mvc\Controller\Plugin\Forward as ForwardPlugin;

class LoginTest extends \PHPUnit_Framework_TestCase {

    public function testLogin() {
        $request = new Request;
        $forward = $this->getMock(ForwardPlugin::class, [], [], '', false);
        $forward->expects($this->once())->method('dispatch')
        ->with('zfcuser', ['action' => 'authenticate']);
        $controller = $this->getMock(
        ResetPassword::class, ['forward', 'getRequest'], [], '', false
        );
        $controller->expects($this->any())->method('getRequest')->willReturn($request);
        $controller->expects($this->any())->method('forward')->willReturn($forward);
        $login = new Login;
        $login->setController($controller);

        $login('1', 'asdf');

        $post = $request->getPost();
        $this->assertEquals('1', $post->identity);
        $this->assertEquals('asdf', $post->credential);
    }

}

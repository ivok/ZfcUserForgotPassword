<?php

namespace ZfcUserForgotPasswordTest\Exception;

use ZfcUserForgotPassword\Exception;

class ExceptionTest extends \PHPUnit_Framework_TestCase {

    public function testForSmoke() {
        $exception = new Exception\Exception;
        $runtimeException = new Exception\RuntimeException;
    }

}

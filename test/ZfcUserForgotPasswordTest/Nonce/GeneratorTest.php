<?php

namespace ZfcUserForgotPasswordTest;

use ZfcUserForgotPassword\Nonce\Generator;

class GeneratorTest extends \PHPUnit_Framework_TestCase {

    public function testGenerator() {
        $generator = new Generator;
        for ($i = 0; $i < 10; $i++) {
            $this->assertTrue(strlen($generator()) == Generator::LENGTH);
        }
    }

}

<?php

namespace ZfcUserForgotPasswordTest\Options;

use ZfcUserForgotPassword\Options\Module as ModuleOptions;

class ModuleTest extends \PHPUnit_Framework_TestCase {

    public function testSettersGetters() {
        $options = new ModuleOptions;
        foreach ([false, true, false, true] as $autoLogin) {
            $options->setAutoLogin($autoLogin);
            $this->assertEquals($autoLogin, $options->getAutoLogin());
        }
    }

    /**
     * @dataProvider dataConfig
     * @param array $config
     * @param bool $expectedAutoLogin
     */
    public function testConfig($config, $expectedAutoLogin) {
        $options = new ModuleOptions($config);
        $this->assertEquals($expectedAutoLogin, $options->getAutoLogin());
    }

    public static function dataConfig() {
        return [
            [[], true],
            [['auto_login' => true], true],
            [['auto_login' => false], false],
        ];
    }

}

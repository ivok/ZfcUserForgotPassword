<?php

namespace ZfcUserForgotPassword\Entity;

use DateTime;

class ResetTest extends \PHPUnit_Framework_TestCase {

    /**
     * @expectedException \ZfcUserForgotPassword\Exception\Exception
     */
    public function testEmptyConstructor() {
        $reset = new Reset;
        $reset->getNonce();
    }

    /**
     * @dataProvider dataBadNonceGenerator
     * @expectedException \ZfcUserForgotPassword\Exception\Exception
     */
    public function testBadNonceGenerator() {
        $reset = new Reset(function() {
            return '';
        });
        $reset->getNonce();
    }

    public static function dataBadNonceGenerator() {
        return [
            [null],
            [''],
        ];
    }

    /**
     * @dataProvider dataGoodNonce
     */
    public function testBadThenGoodNonceGenerator($nonce) {
        $reset = new Reset(function() {
            return '';
        });
        try {
            $reset->getNonce();
            $this->fail();
        } catch (\Exception $exception) {

        }
        $reset->setNonceGenerator(function() use ($nonce) {
            return $nonce;
        });
        $this->assertEquals($nonce, $reset->getNonce());
    }

    /**
     * @dataProvider dataGoodNonce
     * @param string $nonce
     */
    public function testValidConstructor($nonce) {
        $reset = new Reset(function() use ($nonce) {
            return $nonce;
        });
        $this->assertEquals($nonce, $reset->getNonce());
    }

    public static function dataGoodNonce() {
        return [
            ['a'],
            ['12345'],
            ['abcdefghijklmnop'],
        ];
    }

    public function testGetCreatedOnDefault() {
        $reset = new Reset;
        $this->assertInstanceOf(\DateTime::class, $reset->getCreatedOn());
    }

    /**
     * @dataProvider dataUserSetGet
     * @param string $user
     */
    public function testUserSetGet($user) {
        $reset = new Reset;
        $reset->setUser($user);
        $this->assertSame(intval($user), $reset->getUser());
    }

    public static function dataUserSetGet() {
        return [
            [45],
            ['6789'],
        ];
    }

    public function testSetCreatedOnCloned() {
        $reset = new Reset;
        $date = new DateTime('-10 days');
        $reset->setCreatedOn($date);
        $orig = clone $date;

        $this->assertEquals(
        $date->getTimestamp(), $reset->getCreatedOn()->getTimestamp()
        );

        $date->modify('+1000 days');
        $this->assertNotEquals(
        $date->getTimestamp(), $reset->getCreatedOn()->getTimestamp()
        );
    }

}

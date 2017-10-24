<?php

namespace ZfcUserForgotPasswordTest\Sender;

use ZfcUserForgotPassword\Sender\BlackholeFactory;
use ZfcUserForgotPassword\Sender\Blackhole;
use ZfcUserForgotPassword\Entity\Reset;
use Interop\Container\ContainerInterface;
use Faker\Factory as FakerFactory;

class BlackholeTest extends \PHPUnit_Framework_TestCase {

    public function testFactory() {
        $faker = FakerFactory::create();
        $container = $this->getMock(ContainerInterface::class);
        $factory = new BlackholeFactory;
        $blackhole = $factory($container, Blackhole::class);
        $blackhole->send($faker->safeEmail, new Reset); // does nothing
    }

}

<?php

namespace ZfcUserForgotPassword\Sender;

use ZfcUserForgotPassword\Entity\Reset;

class Blackhole implements SenderInterface {

    public function send($email, Reset $reset) {

    }

}

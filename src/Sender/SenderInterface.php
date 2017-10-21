<?php

namespace ZfcUserForgotPassword\Sender;

use ZfcUserForgotPassword\Entity\Reset;

interface SenderInterface {

    public function send($email, Reset $reset);
}

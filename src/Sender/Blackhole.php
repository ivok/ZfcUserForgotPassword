<?php

namespace ZfcUserForgotPassword\Sender;

use ZfcUserForgotPassword\Entity\Reset;

class Blackhole implements SenderInterface {

    public function send($email, Reset $reset) {
        /**
         * In your implementation, you'll want to direct the user to:
         * <scheme/host>/user/reset_password/<userID>/<nonce>
         */
    }

}

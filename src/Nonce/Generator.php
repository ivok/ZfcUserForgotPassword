<?php

namespace ZfcUserForgotPassword\Nonce;

use Zend\Math\Rand;

class Generator {

    /**
     * Avoid:
     * - vowels so we don't spell any bad words.
     * - similar-looking characters.
     */
    const LENGTH = 8;
    const CHAR_LIST = 'BCDFGHJKLMNPQRTVWXZ2346789';

    public function __invoke() {
        return Rand::getString(static::LENGTH, static::CHAR_LIST);
    }

}

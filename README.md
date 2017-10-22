# ZfcUserForgotPassword

[![Build Status](https://travis-ci.org/dillchuk/ZfcUserForgotPassword.svg?branch=master)](https://travis-ci.org/dillchuk/ZfcUserForgotPassword)

## Installation
1. Install in `modules.config.php`:
~~~
return [
    ..., 'ZfcUser', 'ZfcUserForgotPassword', ...
];
~~~

2. Import the database table in [data/reset.sql](data/reset.sql).

3. Install [config/zfcuserforgotpassword.global.php.dist](config/zfcuserforgotpassword.global.php.dist) (into config/autoload) and set up a useful sender.

4. Finally, navigate to `/user/forgot-password`

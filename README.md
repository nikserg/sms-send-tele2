# sms-send-tele2
Module for SMS send using Tele2 API
## Install
`composer require antar/sms_send_tele2`
## Example
````
$sender = new \Antar\SmsSendTele2\Tele2SmsSender('login', 'password', 'SenderName');
$sender->send('70000000000', 'TextMessage');`

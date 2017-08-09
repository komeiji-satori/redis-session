<?php
include 'RediSession.php';
$rs = new RediSession('127.0.0.1', 6379);
//$rs->setid('sessid');
$rs->set('username', 'SatoriKagurazaka');
//$rs->set(['username' => 'SatoriMoe', 'password' => md5(123456)]);
print_r($rs->get('username'));
//print_r($rs->getAll());
//print_r($rs->revoke(123));
//print_r($rs->unset('username'));
//print_r($rs->getid());
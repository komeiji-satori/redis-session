<?php
include 'RediSession.php';
$rs = new RediSession('127.0.0.1', 6379);
$rs->set('username', 'SatoriKagurazaka');
print_r($rs->get('username'));
//print_r($rs->getAll());
//print_r($rs->unset('username'));
//print_r($rs->getid());
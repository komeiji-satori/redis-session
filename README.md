# redis-session
基于Redis的用户Session管理


### 示例代码：
Basic:
```
<?php
$rs = new RediSession('127.0.0.1', 6379);
$rs->set('username', 'SatoriKagurazaka');
print_r($rs->get('username'));
```
With Auth:
```
<?php
$rs = new RediSession('127.0.0.1', 6379,'#pass.word');
```
Set Session Exipre Time
```
<?php
$rs = new RediSession('127.0.0.1', 6379,'#pass.word',86400);
```
Set Cookie Name
```
<?php
$rs = new RediSession('127.0.0.1', 6379,'#pass.word',86400,'RSESSID');
```
### API

 1. string $rs->getid()           //获取用户的redis cookie id
 2. bool $rs->set(key,value)    //设置redis中用户的session key和value
 3. string $rs->get(key)          //获取redis中用户的session key对应的value
 4. bool $rs->unset(key)        //删除redis中用户的session key
 5. array $rs->getAll()          //获取redis中用户的所有session key和value
 6. bool $rs->setid(session id)           //设置指定的session id
 7. bool $rs->revoke(session id)     //销毁指定session id
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
 3. bool $rs->set([key=>value,key2=>value2])          //根据数组设置redis中用户的session key和value
 4. string $rs->get(key)          //获取redis中用户的session key对应的value
 5. bool $rs->unset(key)        //删除redis中用户的session key
 6. array $rs->getAll()          //获取redis中用户的所有session key和value
 7. bool $rs->setid(session id)           //操作指定的session id
 8. bool $rs->revoke(session id)     //销毁指定session id
 9. array $rs->mset([[session1 => value1],[session2 => value2]])  //设置多个session id的value
 10. array $rs->mget([session1,session2])  //获取多个session id的value
 11. int $rs->mdel([session1,session2])      //删除多个session id
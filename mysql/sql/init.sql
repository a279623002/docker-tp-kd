#创建数据库
CREATE DATABASE `tp_kd` CHARACTER SET utf8 COLLATE utf8_unicode_ci;
#给予test用户tp_kd数据库的权限
grant all PRIVILEGES on tp_kd.* to test@'%' identified by '123456';
flush privileges;

#进入tp_kd数据库
use tp_kd;

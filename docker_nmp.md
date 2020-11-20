## docker ubuntu搭建nmp环境--镜像构建

#### 目录结构

```
.
├── app
│   ├── ...
|	└── ...
├── docker-compose.yml
├── mysql
│   ├── data
│   │   ├── ...
|	|	└── ...
│   ├── Dockerfile
│   ├── init
│   │   └── init.sql
│   ├── mysqld.cnf
│   └── sql
│       ├── init.sql
│       └── role.sql
├── nginx
│   ├── conf
│   │   └── nginx.conf
│   ├── conf.d
│   │   └── tp-kd.test.conf
│   └── Dockerfile
├── php
│   ├── conf
│   │   └── sources.list
│   └── Dockerfile
└── ubuntu
    ├── conf
    │   └── sources.list
    └── Dockerfile
```

#### 搭建nginx

###### 目录结构

```
nginx
├── conf
│   └── nginx.conf
├── conf.d
│   └── tp-kd.test.conf
└── Dockerfile
```

* Dockerfile

```
FROM nginx:mainline

COPY conf/nginx.conf /etc/nginx/nginx.conf

# set -ex 
# -e导致命令停止任何错误.更典型的语法是用&&和/或停止任何错误.
# x使shell输出正在运行的每个命令.这对于调试脚本很有用.
RUN set -ex \
    && rm -rf /etc/nginx/cond.d/default.conf
```

* conf>nginx.conf

```
user  www-data;
worker_processes  1;

error_log  /var/log/nginx/error.log warn;
pid        /var/run/nginx.pid;


events {
    worker_connections  1024;
}


http {
    include       /etc/nginx/mime.types;
    default_type  application/octet-stream;

    log_format  main  '$remote_addr - $remote_user [$time_local] "$request" '
                      '$status $body_bytes_sent "$http_referer" '
                      '"$http_user_agent" "$http_x_forwarded_for"';

    access_log  /var/log/nginx/access.log  main;

    sendfile        on;
    #tcp_nopush     on;

    keepalive_timeout  65;

    #gzip  on;

    include /etc/nginx/conf.d/*.conf;
}
```

* conf.d>tp-kd.test.conf

```
server {
    listen       80;
    server_name  tp-kd.test;
    root   /app/;
    index  index.php index.html;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass php:9000;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include        fastcgi_params;
    }
}
```

#### 搭建php

###### 目录结构

```
php/
├── conf
│   └── sources.list
└── Dockerfile
```

* Dockerfile

```
FROM php:7.3-fpm

COPY conf/sources.list /etc/apt/sources.list

ADD https://mirrors.aliyun.com/composer/composer.phar /usr/local/bin/composer

RUN set -xe \
    && apt-get update \
    && apt-get install -y --no-install-recommends \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
        libzip-dev \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install gd pdo_mysql zip exif opcache \
    && pecl install -o -f redis-5.0.2 \
    && docker-php-ext-enable redis \
    && chmod a+x /usr/local/bin/composer \
    && rm -rf /tmp/pear \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /app
```

* conf>sources.list

```
deb http://mirrors.ustc.edu.cn/debian/ buster main contrib non-free
deb-src http://mirrors.ustc.edu.cn/debian/ buster main contrib non-free

deb http://mirrors.ustc.edu.cn/debian/ buster-updates main contrib non-free
deb-src http://mirrors.ustc.edu.cn/debian/ buster-updates main contrib non-free

deb http://mirrors.ustc.edu.cn/debian-security/ buster/updates main contrib non-free
deb-src http://mirrors.ustc.edu.cn/debian-security/ buster/updates main contrib non-free
```

#### 搭建mysql

###### 目录结构

```
mysql/
├── data
│   ├── ...
│   └── ...
├── Dockerfile
├── init
│   └── init.sql
├── mysqld.cnf
└── sql
    ├── init.sql
    └── role.sql
```

* Dockerfile

```
FROM mysql:5.5.62

# 添加配置文件包括编码格式设置
ADD mysqld.cnf /etc/mysql/mysql.conf.d/mysqld.cnf
```

* mysqld.cnf

```
[client]
port=3306
socket = /var/run/mysqld/mysqld.sock
default-character-set=utf8
[mysql]
no-auto-rehash
auto-rehash
default-character-set=utf8
[mysqld]
default-character-set=utf8
character_set_server=utf8
###basic settings
server-id = 2
pid-file    = /var/run/mysqld/mysqld.pid
socket        = /var/run/mysqld/mysqld.sock
datadir        = /var/lib/mysql
#log-error    = /var/lib/mysql/error.log
# By default we only accept connections from localhost
#bind-address    = 127.0.0.1
# Disabling symbolic-links is recommended to prevent assorted security risks
symbolic-links=0
character-set-server = utf8
sql_mode="NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION"
default-storage-engine=INNODB
transaction_isolation = READ-COMMITTED
auto_increment_offset = 1
connect_timeout = 20
max_connections = 3500
wait_timeout=86400
interactive_timeout=86400
interactive_timeout = 7200
log_bin_trust_function_creators = 1
wait_timeout = 7200
sort_buffer_size = 32M
join_buffer_size = 128M
max_allowed_packet = 1024M
tmp_table_size = 2097152
explicit_defaults_for_timestamp = 1
read_buffer_size = 16M
read_rnd_buffer_size = 32M
query_cache_type = 1
query_cache_size = 2M
table_open_cache = 1500
table_definition_cache = 1000
thread_cache_size = 768
back_log = 3000
open_files_limit = 65536
skip-name-resolve
########log settings########
log-output=FILE
general_log = ON
general_log_file=/var/lib/mysql/general.log
slow_query_log = ON
slow_query_log_file=/var/lib/mysql/slowquery.log
long_query_time=10
#log-error=/var/lib/mysql/error.log
log_queries_not_using_indexes = OFF
log_throttle_queries_not_using_indexes = 0
#expire_logs_days = 120
min_examined_row_limit = 100
########innodb settings########
innodb_io_capacity = 4000
innodb_io_capacity_max = 8000
innodb_buffer_pool_size = 6144M
innodb_file_per_table = on
innodb_buffer_pool_instances = 20
innodb_buffer_pool_load_at_startup = 1
innodb_buffer_pool_dump_at_shutdown = 1
innodb_log_file_size = 300M
innodb_log_files_in_group = 2 
innodb_log_buffer_size = 16M
innodb_undo_logs = 128
#innodb_undo_tablespaces = 3
#innodb_undo_log_truncate = 1
#innodb_max_undo_log_size = 2G
innodb_flush_method = O_DIRECT
innodb_flush_neighbors = 1
innodb_purge_threads = 4
innodb_large_prefix = 1
innodb_thread_concurrency = 64
innodb_print_all_deadlocks = 1
innodb_strict_mode = 1
innodb_sort_buffer_size = 64M
innodb_flush_log_at_trx_commit=1
innodb_autoextend_increment=64
innodb_concurrency_tickets=5000
innodb_old_blocks_time=1000
innodb_open_files=65536
innodb_stats_on_metadata=0
innodb_file_per_table=1
innodb_checksum_algorithm=0
#innodb_data_file_path=ibdata1:60M;ibdata2:60M;autoextend:max:1G
innodb_data_file_path = ibdata1:12M:autoextend
#innodb_temp_data_file_path = ibtmp1:500M:autoextend:max:20G
#innodb_buffer_pool_dump_pct = 40
#innodb_page_cleaners = 4
#innodb_purge_rseg_truncate_frequency = 128
binlog_gtid_simple_recovery=1
#log_timestamps=system
##############
delayed_insert_limit = 100
delayed_insert_timeout = 300
delayed_queue_size = 1000
delay_key_write = ON
disconnect_on_expired_password = ON
div_precision_increment = 4
end_markers_in_json = OFF
eq_range_index_dive_limit = 10
innodb_adaptive_flushing = ON
innodb_adaptive_hash_index = ON
innodb_adaptive_max_sleep_delay = 150000
#innodb_additional_mem_pool_size = 2097152
innodb_autoextend_increment = 64
innodb_autoinc_lock_mode = 1
```

* init>init.sql

```
# docker-composer 初始化mysql容器执行的sql文件
# 以下按顺序执行
# 初始化数据库，添加test用户,并使用该数据库
source /opt/sql/init.sql;
use tp_kd;
# 导入数据，需注意在里面也有编码设置
# 如果容器设置的编码不生效可检查这里
source /opt/sql/role.sql;
```

* sql>init.sql

```
#创建数据库
CREATE DATABASE `tp_kd` CHARACTER SET utf8 COLLATE utf8_unicode_ci;
#给予test用户tp_kd数据库的权限
grant all PRIVILEGES on tp_kd.* to test@'%' identified by '123456';
flush privileges;

#进入tp_kd数据库
use tp_kd;
```

* sql>role.sql

```
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";
/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
--
-- 创建表插入数据
--
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
```

## 构建镜像

###### 分别构建mysql,php,nginx镜像，docker-compose使用镜像 运行

```
# 如构建mysql镜像，在建立的mysql目录下
$ docker build -t siro/mysql .
```

###### 构建后列表如下

```
$ docker images
REPOSITORY           TAG                 IMAGE ID            CREATED             SIZE
siro/mysql           latest              909a0f088464        3 hours ago         205MB
siro/nginx           latest              58076025c2a7        23 hours ago        133MB
siro/php             latest              f9af36bc2ff9        26 hours ago        415MB
```


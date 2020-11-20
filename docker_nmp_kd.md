## docker ubuntu搭建nmp环境部署--运行项目

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

## docker-compose.yml

* 定义version--根据docker version对照官网获取

```
# 官网 -- 获取version值
# https://docs.docker.com/compose/compose-file/
```

* docker-compose.yml

```
version: "3.8"
services: 
    mysql:
        image: siro/mysql #以构建好的mysql镜像，下同
        container_name: tp-kd-mysql #自命名，项目数据库配置用到
        volumes:
            - ./mysql/data:/var/lib/mysql #把容器的数据文件夹映射到 ./mysql/data
            - ./mysql/init:/docker-entrypoint-initdb.d/ #把初始化的文件夹映射到 ./mysql/init，只会在第一次启动容器时调用一次
            - ./mysql/sql:/opt/sql #把需要执行的sql文件映射到./mysql/sql
        environment:
            - MYSQL_ROOT_PASSWORD=root #设置数据库root密码
        ports:
            - 23306:3306 #把容器的3306端口映射到本机的23306
        labels:
            - traefik.enable=false #关闭网关
        restart: always #Docker重启时容器自动启动
        command: --character-set-server=utf8 --collation-server=utf8_unicode_ci

    nginx:
        image: siro/nginx
        container_name: tp-kd-nginx
        volumes:
            - ./app:/app #将容器的项目路径映射到本地项目路径，也可以说把本地项目挂载到容器
            - ./nginx/conf.d:/etc/nginx/conf.d/ #映射域名配置
        labels:
            - "traefik.http.routers.tp-kd-nginx.rule=Host(`tp-kd.test`)"
        environment:
            FASTCGI_PASS: php:9000
            DOCUMENT_ROOT: /app #设置根目录
        ports:
            - "2380:80"

    php:
        image: siro/php
        container_name: tp-kd-php
        tty: true
        volumes:
            - ./app:/app
        environment:
            DB_HOST: mysql #此处mysql指的是服务，即本文件的services:mysql
            DOCUMENT_ROOT: /app #设置根目录
        labels:
            - traefik.enable=false
```

* 修改项目数据库配置（tp3）

```
    'DB_TYPE'               =>  'mysql',
    'DB_HOST'      =>  'tp-kd-mysql', // 修改为镜像服务名称，即services:mysql:container_name:
    'DB_NAME'               =>  'tp_kd',          // 数据库名
    'DB_USER'               =>  'test',      // 用户名
    'DB_PWD'                =>  '123456',          // 密码
    'DB_PORT'               =>  '3306',        // 端口
    'DB_PREFIX'             =>  'kd_',    // 数据库表前缀
    'DB_PARAMS'          	=>  array(), // 数据库连接参数
    'DB_DEBUG'  			=>  TRUE, // 数据库调试模式 开启后可以记录SQL日志
    'DB_FIELDS_CACHE'       =>  true,        // 启用字段缓存
    'DB_CHARSET'            =>  'utf8',      // 数据库编码默认采用utf8
    'DB_DEPLOY_TYPE'        =>  0, // 数据库部署方式:0 集中式(单一服务器),1 分布式(主从服务器)
    'DB_RW_SEPARATE'        =>  false,       // 数据库读写是否分离 主从式有效
    'DB_MASTER_NUM'         =>  1, // 读写分离后 主服务器数量
    'DB_SLAVE_NO'           =>  '', // 指定从服务器序号
```

* docker-compose运行

```
$ docker-compose up
```

* docker-compose终止

```
$ docker-compose down #同时删除容器
```

###### tip

> 进入mysql容器里操作数据库

```
$ docker exec -it siro/mysql /bin/bash #进入容器
$ mysql -u root -p #进入mysql
#查看外部访问编码
SHOW VARIABLES LIKE 'collation_%';
#查看数据库字符集：
SHOW VARIABLES LIKE 'character_set_%';
#设置外部访问编码
SET NAMES 'utf8';
#等价于下面三句
SET character_set_client = utf8;
SET character_set_results = utf8;
SET character_set_connection = utf8;
# 还需要修改mysqld.cnf的编码设置，重启容器，restart
```


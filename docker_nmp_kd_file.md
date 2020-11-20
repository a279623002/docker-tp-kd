## docker ubuntu搭建nmp环境部署--使用dockerfile

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

* docker-compose.yml

```
version: "3.8"
services: 
    mysql:
        build: ./mysql #基于mysql目录下的dockerfile构建
        image: kd/mysql #定义构建镜像的名称
        container_name: tp-kd-mysql
        volumes:
            - ./mysql/data:/var/lib/mysql #把容器的数据文件夹映射到 ./mysql/data
            - ./mysql/init:/docker-entrypoint-initdb.d/ #把初始化的文件夹映射到 ./mysql/init
            - ./mysql/sql:/opt/sql
        environment:
            - MYSQL_ROOT_PASSWORD=root
        ports:
            - 23306:3306 #把容器的3306端口映射到本机的23306
        labels:
            - traefik.enable=false
        restart: always
        command: --character-set-server=utf8mb4 --collation-server=utf8mb4_unicode_ci

    nginx:
        build: ./nginx
        image: kd/nginx
        container_name: tp-kd-nginx
        volumes:
            - ./app:/app
            - ./nginx/conf.d:/etc/nginx/conf.d/
        labels:
            - "traefik.http.routers.tp-kd-nginx.rule=Host(`tp-kd.test`)"
        environment:
            FASTCGI_PASS: php:9000
            DOCUMENT_ROOT: /app
        ports:
            - "2380:80"

    php:
        build: ./php
        image: kd/php
        container_name: tp-kd-php
        tty: true
        volumes:
            - ./app:/app
        environment:
            DB_HOST: mysql
            DOCUMENT_ROOT: /app
        labels:
            - traefik.enable=false

```

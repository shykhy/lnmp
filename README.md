## 说明
这是一个基于alpine的thinkphp5创建的一个lnmp工具

### 目录
```
├── build
│   └── php
│       └── Dockerfile
├── conf
│   ├── mysql
│   │   ├── conf.d
│   │   │   └── mysql.cnf
│   │   ├── my.cnf
│   │   ├── mysql.cnf
│   │   └── mysql.conf.d
│   │       └── mysqld.cnf
│   ├── nginx
│   │   ├── conf.d
│   │   │   └── default.conf
│   │   └── nginx.conf
│   ├── php
│   │   ├── php-fpm.conf
│   │   ├── php-fpm.d
│   │   │   ├── docker.conf
│   │   │   ├── www.conf
│   │   │   ├── www.conf.default
│   │   │   └── zz-docker.conf
│   │   └── php.ini
│   └── redis
│       └── redis.conf
├── docker-compose.yml
├── logs
│   └── nginx
│       ├── access.log
│       └── error.log
├── README.md
└── redisdata
    └── README.md
``` 
### 准备
下载docker和docker-compose工具
参考官网
https://hub.docker.com

### 操作
```
git clone https://github.com/shykhy/lnmp.git # 克隆代码到本地
cd lnmp 
docker-compose up -d # 运行compose
```
### 附录
#### docker-compose 文件
```
version: "3"
services: 
    fpm:
        image: php:7.4-fpm-alpine
        container_name: fpm
        volumes:
            - ./web:/php
            - ./conf/php/php-fpm.conf:/usr/local/etc/php-fpm.conf
            - ./conf/php/php-fpm.d:/usr/local/etc/php-fpm.d
            - ./conf/php/php.ini:/usr/local/etc/php/php.ini
        networks:
            lnmpnet:
                ipv4_address: 192.168.20.3
    nginx:
        image: nginx:1.17.6-alpine
        container_name: nginx
        ports: 
            - 80:80
        networks:
            lnmpnet:
                ipv4_address: 192.168.20.4
        volumes:
            - ./web:/usr/share/nginx/html
            - ./conf/nginx/conf.d:/etc/nginx/conf.d
            - ./conf/nginx/nginx.conf:/etc/nginx/nginx.conf
     mysql:
       image: mysql:5.7
       container_name: mysqld
       restart: always
        ports: 
            - 3306:3306
       volumes:
            - ./mysqldata:/var/lib/mysql
            - ./conf/mysql:/etc/mysql
       environment:
            MYSQL_ROOT_PASSWORD: 123456
       networks:
            lnmpnet:
                ipv4_address: 192.168.20.5
    redis:
       image: redis:5.0-alpine
       container_name: redis
        ports: 
            - 6379:6379
       volumes:
            - ./redisdata:/data
            - ./conf/redis/redis.conf:/usr/local/etc/redis/redis.conf
       networks:
        - lnmpnet
networks:
    lnmpnet:
        driver: bridge
        ipam:
             config:
                - subnet: "192.168.20.0/24"
```
#### nginx配置文件
conf/nginx/conf.d/default.conf
```
location / {
    root   /usr/share/nginx/html;
    index   index.html index.php;
    if ( -f $request_filename) {
        break;
    }
    if ( !-e $request_filename) {
        rewrite ^(.*)$ /index.php/$1 last;
        break;
    }
}
location ~ .+\.php($|/) {
    root /php/public;
    fastcgi_pass   192.168.100.3:9000;
    fastcgi_index  index.php;
    fastcgi_split_path_info  ^(.+\.php)(.*)$;
    fastcgi_param  SCRIPT_FILENAME $document_root$fastcgi_script_name;
    fastcgi_param  PATH_INFO $fastcgi_path_info;
    include fastcgi_params;
}
```
#### build
```
FROM php:7.4-fpm-alpine
# PHP安装
RUN echo https://mirrors.aliyun.com/alpine/v3.11/main/ > /etc/apk/repositories \
    && echo https://mirrors.aliyun.com/alpine/v3.11/community/ >> /etc/apk/repositories \
    && apk update && apk upgrade \
    && apk add autoconf gcc \
    && wget https://github.com/phpredis/phpredis/archive/5.0.0.tar.gz \
    && tar -zxvf 5.0.0.tar.gz \
    && cd phpredis-5.0.0 \
    && /usr/local/bin/phpize \
    && ./configure --with-php-config=/usr/local/bin/php-config \
    && make \
    && make install \
    && apk add shadow \
    && usermod -u 1000 www-data \
    && groupmod -g 1000 www-data \

# pdo_mysql安装
    && docker-php-ext-install pdo_mysql mysqli pcntl gd
```
#### 下载thinkphp5到web
> 下载地址
```
http://www.thinkphp.cn/donate/download/id/1278.html
```
> 解压到web文件夹
```
uzip xxxx.tar.gz  -d .
```

FROM php:7.4-fpm-alpine
RUN echo https://mirrors.aliyun.com/alpine/v3.11/main/ > /etc/apk/repositories && \
	echo https://mirrors.aliyun.com/alpine/v3.11/community/ >> /etc/apk/repositories && \
	apk update && apk upgrade && \
    	apk add shadow && \
    	usermod -u 1000 www-data && \
    	groupmod -g 1000 www-data

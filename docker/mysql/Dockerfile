FROM mysql/mysql-server:latest

ENV MYSQL_DATABASE=laravel \
    MYSQL_USER=phper \
    MYSQL_PASSWORD=secret \
    MYSQL_ROOT_PASSWORD=secret \
    TZ=Asia/Tokyo

COPY ./docker/mysql/my.cnf /etc/my.cnf
RUN chmod 644 /etc/my.cnf
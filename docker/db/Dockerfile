FROM mysql:5.7

RUN { \
        printf '%s\n' \
        '[mysqld]' \
        'character-set-server = utf8mb4' \
        'collation-server = utf8mb4_unicode_ci' \
        'innodb_autoextend_increment = 4' \
        'innodb_log_file_size = 4M'; \
    } > /etc/mysql/conf.d/000-mysql.cnf

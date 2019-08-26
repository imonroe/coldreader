FROM mysql:5.7.22
COPY low-memory-my.cnf /etc/mysql/my.cnf
RUN chmod 777 /var/log/mysqld.log
CMD ["mysqld"]

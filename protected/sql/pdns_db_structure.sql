# /usr/share/doc/pdns-backend-mysql.
CREATE DATABASE powerdns;
GRANT ALL ON powerdns.* TO 'powerdns'@'localhost'
IDENTIFIED BY 'whalallapowerdns';
GRANT ALL ON powerdns.* TO 'powerdns'@'localhost'
IDENTIFIED BY 'whalallapowerdns';
FLUSH PRIVILEGES;
### Базу создали, права выставили, переходим к созданию таблиц.
USE powerdns;
### Таблица для доменов
CREATE TABLE domains (
  id              INT AUTO_INCREMENT,
  name            VARCHAR(255) UNIQUE NOT NULL,
  master          VARCHAR(128) DEFAULT NULL,
  last_check      INT DEFAULT NULL,
  type            VARCHAR(6)          NOT NULL,
  notified_serial INT DEFAULT NULL,
  account         VARCHAR(40) DEFAULT NULL,
  PRIMARY KEY (id)
);
### Создаем индекс для таблицы
CREATE UNIQUE INDEX name_index ON domains (name);

### Создаем таблицу записей
CREATE TABLE records (
  id          INT AUTO_INCREMENT,
  domain_id   INT DEFAULT NULL,
  name        VARCHAR(255) DEFAULT NULL,
  type        VARCHAR(6) DEFAULT NULL,
  content     VARCHAR(255) DEFAULT NULL,
  ttl         INT DEFAULT NULL,
  prio        INT DEFAULT NULL,
  change_date INT DEFAULT NULL,
  PRIMARY KEY (id)
);
### Создаем индексы для таблицы
CREATE INDEX rec_name_index ON records (name);
CREATE INDEX nametype_index ON records (name, type);
CREATE INDEX domain_id ON records (domain_id);
### Создаем таблицу мастер зон
CREATE TABLE supermasters (
  ip         VARCHAR(25)  NOT NULL,
  nameserver VARCHAR(255) NOT NULL,
  account    VARCHAR(40) DEFAULT NULL
);


INSERT INTO domains (name, type) VALUES ('thegame2.ru', 'NATIVE');
INSERT INTO records (domain_id, name, content, type, ttl, prio)
  VALUES (1, 'thegame2.ru', 'localhost agilovr@gmail.com 1', 'SOA', 86400, NULL);
INSERT INTO records (domain_id, name, content, type, ttl, prio)
  VALUES
  (1, 'test1.thegame2.ru', 'ns1.thegame2.ru', 'NS', 86400, NULL),
  (1, 'test1.thegame2.ru', 'ns2.thegame2.ru', 'NS', 86400, NULL),
  (1, 'test1.thegame2.ru', '62.76.42.250', 'A', 120, NULL),
  (1, 'test2.thegame2.ru', 'ns1.thegame2.ru', 'NS', 86400, NULL),
  (1, 'test2.thegame2.ru', 'ns2.thegame2.ru', 'NS', 86400, NULL),
  (1, 'test2.thegame2.ru', '62.76.42.250', 'A', 120, NULL);
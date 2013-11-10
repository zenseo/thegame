# drop database 'game';
CREATE DATABASE game
  DEFAULT CHARACTER SET utf8;

CREATE USER 'game@localhost'
  IDENTIFIED BY 'game';

GRANT ALL PRIVILEGES ON *.game TO 'game@localhost';

USE game;
# drop table if exists goals;
CREATE TABLE users (
  id            INTEGER(8) ZEROFILL NOT NULL AUTO_INCREMENT PRIMARY KEY,
  id_parent     INTEGER(8) ZEROFILL DEFAULT NULL,
  id_partner    INTEGER(8) ZEROFILL DEFAULT NULL,
  first_name    VARCHAR(100)        NOT NULL,
  second_name   VARCHAR(100),
  patronymic    VARCHAR(100),
  phone         VARCHAR(13) UNIQUE  NOT NULL,
  email         VARCHAR(100) UNIQUE NOT NULL,
  password      VARCHAR(255)        NOT NULL,
  role          VARCHAR(100)        NOT NULL DEFAULT 'user',
  status        VARCHAR(255)        NOT NULL DEFAULT 'Онлайн',
  system_status SMALLINT            NOT NULL DEFAULT 0,
  allowed       TEXT DEFAULT null,
  forbidden     TEXT DEFAULT null,
  last_session  DATETIME            NOT NULL,
  created       DATETIME            NOT NULL DEFAULT current_timestamp(),
  modified      DATETIME ON UPDATE CURRENT_TIMESTAMP,
  deleted       BOOLEAN DEFAULT FALSE
)
  ENGINE InnoDB
  DEFAULT CHARSET =utf8
  AUTO_INCREMENT =411;
INSERT INTO users (password, first_name, patronymic, phone, email, role) VALUES (md5(
                                                                                     'mywhalalla'), 'Админ', 'Всемогущий', '+79132229911', 'alpha', 'admin');
INSERT INTO users (password, first_name, patronymic, phone, email, role) VALUES (md5(
                                                                                     'asdasd'), 'Роман', 'Геннадьевич', '+79132229915', 'beta', 'member');
INSERT INTO users (password, first_name, patronymic, phone, email, role) VALUES (md5(
                                                                                     'asdasd'), 'Только', 'Зарегился', '+79132229918', 'just', 'user');
INSERT INTO users (password, first_name, patronymic, phone, email, role) VALUES (md5(
                                                                                     'asdasd'), 'Заблокированный', 'Пользователь', '+79132229917', 'blocked', 'blocked');
INSERT INTO users (password, first_name, patronymic, phone, email, role) VALUES (md5(
                                                                                     'asdasd'), 'Иван', 'Иванович', '+79132229916', 'omega', 'member');



# drop table if exists goals;
CREATE TABLE goals (
  id          INTEGER(8) ZEROFILL NOT NULL AUTO_INCREMENT PRIMARY KEY,
  id_parent   INTEGER(8) ZEROFILL DEFAULT NULL,
  id_user     INTEGER(8) ZEROFILL NOT NULL,
  name        VARCHAR(100),
  description VARCHAR(255),
  progress    SMALLINT(3)         NOT NULL DEFAULT 0,
  status      SMALLINT            NOT NULL DEFAULT 0,
  created     DATETIME            NOT NULL DEFAULT current_timestamp(),
  modified    DATETIME ON UPDATE CURRENT_TIMESTAMP,
  deleted     BOOLEAN DEFAULT FALSE
)
  ENGINE InnoDB
  DEFAULT CHARSET =utf8
  AUTO_INCREMENT =31;
ALTER TABLE goals
ADD CONSTRAINT goal_user_rel_fk FOREIGN KEY (id_user)
REFERENCES goals (id)
  ON DELETE CASCADE
  ON UPDATE CASCADE;


# drop table if exists goal_comments;
CREATE TABLE goal_comments (
  id       INTEGER ZEROFILL    NOT NULL AUTO_INCREMENT PRIMARY KEY,
  id_goal  INTEGER(8) ZEROFILL NOT NULL,
  text     VARCHAR(255),
  status   SMALLINT            NOT NULL DEFAULT 0,
  created  DATETIME            NOT NULL DEFAULT current_timestamp(),
  modified DATETIME ON UPDATE CURRENT_TIMESTAMP,
  deleted  BOOLEAN DEFAULT FALSE
)
  ENGINE InnoDB
  DEFAULT CHARSET =utf8
  AUTO_INCREMENT =1;

ALTER TABLE goal_comments
ADD CONSTRAINT goal_comments_goals_rel_fk FOREIGN KEY (id_goal)
REFERENCES users (id)
  ON DELETE CASCADE
  ON UPDATE CASCADE;


# drop table if exists main_menu;
CREATE TABLE main_menu (
  id        INTEGER           NOT NULL PRIMARY KEY,
  parent_id INTEGER DEFAULT 0 NOT NULL,
  label     CHARACTER VARYING(100),
  rule      CHARACTER VARYING(100),
  action    CHARACTER VARYING(100),
  sort      INTEGER,
  active    SMALLINT DEFAULT 1
)
  ENGINE InnoDB;
-- Верхний уровень меню
INSERT INTO main_menu (id, parent_id, label, rule, action, sort, active) VALUES (1, 0, 'Начало', 'indexSite', 'site/index', 1, 1);

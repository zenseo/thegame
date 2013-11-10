SET @OLD_UNIQUE_CHECKS = @@UNIQUE_CHECKS, UNIQUE_CHECKS = 0;
SET @OLD_FOREIGN_KEY_CHECKS = @@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS = 0;
SET @OLD_SQL_MODE = @@SQL_MODE, SQL_MODE = 'TRADITIONAL,ALLOW_INVALID_DATES';

DROP SCHEMA IF EXISTS `test1`;
CREATE SCHEMA IF NOT EXISTS `test1`
  DEFAULT CHARACTER SET utf8
  COLLATE utf8_general_ci;
USE `test1`;

-- -----------------------------------------------------
-- Table `test1`.`dictionary_user_status`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `test1`.`dictionary_user_status`;

CREATE TABLE IF NOT EXISTS `test1`.`dictionary_user_status` (
  `id`     INT         NOT NULL AUTO_INCREMENT COMMENT 'Идентификатор статуса пользователя',
  `name`   VARCHAR(45) NOT NULL
  COMMENT 'Статус пользователя',
  `active` TINYINT(1)  NOT NULL DEFAULT 1
  COMMENT 'Активность',
  PRIMARY KEY (`id`),
  UNIQUE INDEX `name_UNIQUE` (`name` ASC),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC))
  ENGINE = InnoDB
  COMMENT = 'Словарь статусов пользователя';


-- -----------------------------------------------------
-- Table `test1`.`user`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `test1`.`user`;

CREATE TABLE IF NOT EXISTS `test1`.`user` (
  `id`                       INT          NOT NULL AUTO_INCREMENT COMMENT 'Идентификатор пользователя',
  `login`                    VARCHAR(45)  NOT NULL
  COMMENT 'Логин',
  `password`                 VARCHAR(32)  NOT NULL DEFAULT '96e79218965eb72c92a549dd5a330112'
  COMMENT 'Пароль',
  `email`                    VARCHAR(255) NOT NULL
  COMMENT 'Электронная почта',
  `phone`                    VARCHAR(45)  NOT NULL
  COMMENT 'Телефон',
  `created`                  TIMESTAMP    NULL DEFAULT CURRENT_TIMESTAMP
  COMMENT 'Дата создания',
  `updated`                  TIMESTAMP    NULL
  COMMENT 'Последнее изменение',
  `lastname`                 VARCHAR(45)  NULL
  COMMENT 'Фамилия',
  `firstname`                VARCHAR(45)  NOT NULL
  COMMENT 'Имя',
  `surename`                 VARCHAR(45)  NULL
  COMMENT 'Отчество',
  `gender`                   TINYINT(1)   NOT NULL
  COMMENT 'Пол',
  `role`                     VARCHAR(45)  NOT NULL DEFAULT 'user'
  COMMENT 'Роль',
  `department`               INT          NULL
  COMMENT 'Подразделение',
  `birth_date`               DATE         NULL
  COMMENT 'Дата рождения',
  `passport_serial`          INT(4)       NULL
  COMMENT 'Серия паспорта',
  `passport_number`          INT          NULL
  COMMENT 'Номер паспорта',
  `passport_date`            DATE         NULL
  COMMENT 'Дата выдачи паспорта',
  `passport_department_code` VARCHAR(10)  NULL
  COMMENT 'Код подразделения, выдавшего паспорт',
  `birth_place`              VARCHAR(255) NULL
  COMMENT 'Место рождения',
  `labor_contract_number`    INT          NULL
  COMMENT 'Номер трудового договора',
  `labor_contract_date`      DATE         NULL
  COMMENT 'Дата трудового договора',
  `status`                   INT          NULL DEFAULT 2
  COMMENT 'Статус',
  `last_ip`                  VARCHAR(20)  NULL
  COMMENT 'Последний IP адрес',
  `last_mac_address`         VARCHAR(20)  NULL
  COMMENT 'Последний MAC адрес',
  `allowed`                  TEXT         NULL
  COMMENT 'Что можно',
  `forbidden`                TEXT         NULL
  COMMENT 'Что нельзя',
  `position`                 INT          NULL
  COMMENT 'Должность',
  `avatar`                   VARCHAR(255) NULL
  COMMENT 'Аватар',
  `photo`                    VARCHAR(255) NULL
  COMMENT 'Фото',
  UNIQUE INDEX `email_UNIQUE` (`email` ASC),
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_user_UNIQUE` (`id` ASC),
  UNIQUE INDEX `phone_UNIQUE` (`phone` ASC),
  INDEX `last_name_INDEX` (`lastname` ASC),
  UNIQUE INDEX `login_UNIQUE` (`login` ASC),
  INDEX `fk_user_user_status_idx` (`status` ASC),
  CONSTRAINT `fk_user_user_status`
  FOREIGN KEY (`status`)
  REFERENCES `test1`.`dictionary_user_status` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE)
  ENGINE = InnoDB
  COMMENT = 'Пользователи';


-- -----------------------------------------------------
-- Table `test1`.`dictionary_user__history___events`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `test1`.`dictionary_user__history___events`;

CREATE TABLE IF NOT EXISTS `test1`.`dictionary_user__history___events` (
  `id`     INT          NOT NULL
  COMMENT 'Идентификатор события',
  `name`   VARCHAR(100) NOT NULL
  COMMENT 'Название события',
  `active` TINYINT(1)   NOT NULL DEFAULT 1
  COMMENT 'Активность',
  PRIMARY KEY (`id`))
  ENGINE = InnoDB
  COMMENT = 'Словарь - События пользователя';


-- -----------------------------------------------------
-- Table `test1`.`log_user__history`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `test1`.`log_user__history`;

CREATE TABLE IF NOT EXISTS `test1`.`log_user__history` (
  `id`      INT       NOT NULL AUTO_INCREMENT COMMENT 'Идентификатор события',
  `user`    INT       NULL
  COMMENT 'Пользователь',
  `event`   INT       NULL
  COMMENT 'Событие',
  `message` TEXT      NULL
  COMMENT 'Что произошло',
  `created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
  COMMENT 'Когда',
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC),
  INDEX `event_id_INDEX` (`event` ASC),
  INDEX `user_history__user_fk_idx` (`user` ASC),
  INDEX `created_INDEX` (`created` ASC),
  CONSTRAINT `user__history____user_fk`
  FOREIGN KEY (`user`)
  REFERENCES `test1`.`user` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `user__history____event_fk`
  FOREIGN KEY (`event`)
  REFERENCES `test1`.`dictionary_user__history___events` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
  ENGINE = InnoDB
  COMMENT = 'История - События пользователя';


-- -----------------------------------------------------
-- Table `test1`.`customer`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `test1`.`customer`;

CREATE TABLE IF NOT EXISTS `test1`.`customer` (
  `id`           INT          NOT NULL AUTO_INCREMENT COMMENT 'Идентификатор клиента',
  `name`         VARCHAR(255) NOT NULL
  COMMENT 'Название',
  `phone`        VARCHAR(255) NULL DEFAULT NULL
  COMMENT 'Телефон',
  `email`        VARCHAR(255) NULL DEFAULT NULL
  COMMENT 'Электронная почта',
  `address`      VARCHAR(255) NULL DEFAULT NULL
  COMMENT 'Адрес',
  `2gis_id`      BIGINT       NULL DEFAULT NULL
  COMMENT 'Идентификатор 2gis',
  `created`      TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP
  COMMENT 'Создан',
  `updated`      TIMESTAMP    NULL DEFAULT NULL
  COMMENT 'Последнее обновление',
  `responsible`  INT          NULL DEFAULT NULL
  COMMENT 'Ответсвенный',
  `note`         TEXT         NULL DEFAULT NULL
  COMMENT 'Коментарий',
  `in_work`      INT          NULL DEFAULT NULL
  COMMENT 'В работе',
  `removed`      TINYINT(1)   NOT NULL DEFAULT FALSE
  COMMENT 'Удален',
  `sales_status` INT          NULL DEFAULT 1
  COMMENT 'Статус продаж',
  `creator`      INT          NULL DEFAULT NULL
  COMMENT 'Кто добавил',
  `updater`      INT          NULL DEFAULT NULL
  COMMENT 'Кто последний раз обновил',
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC),
  INDEX `name_INDEX` (`name` ASC))
  ENGINE = InnoDB
  COMMENT = 'Клиенты';


-- -----------------------------------------------------
-- Table `test1`.`contact`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `test1`.`contact`;

CREATE TABLE IF NOT EXISTS `test1`.`contact` (
  `id`           INT          NOT NULL AUTO_INCREMENT COMMENT 'Идентификатор контакта',
  `lastname`     VARCHAR(150) NULL DEFAULT NULL
  COMMENT 'Фамилия',
  `firstname`    VARCHAR(150) NOT NULL
  COMMENT 'Имя',
  `surename`     VARCHAR(150) NULL DEFAULT NULL
  COMMENT 'Отчество',
  `comment`      TEXT         NULL DEFAULT NULL
  COMMENT 'Комментарий',
  `email`        VARCHAR(150) NULL DEFAULT NULL
  COMMENT 'Электронная почта',
  `icq`          INT          NULL DEFAULT NULL
  COMMENT 'ICQ',
  `last_contact` TIMESTAMP    NULL DEFAULT NULL
  COMMENT 'Дата последнего контакта',
  `created`      TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP
  COMMENT 'Создан',
  `updated`      TIMESTAMP    NULL DEFAULT NULL
  COMMENT 'Обновлен',
  `creator`      INT          NULL DEFAULT NULL
  COMMENT 'Кто создал',
  `updater`      INT          NULL DEFAULT NULL
  COMMENT 'Кто обновил',
  PRIMARY KEY (`id`),
  INDEX `lastname_INDEX` (`lastname` ASC),
  INDEX `updated_INDEX` (`updated` ASC),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC),
  INDEX `fk_contact_user1_idx` (`creator` ASC),
  INDEX `fk_contact_user2_idx` (`updater` ASC),
  CONSTRAINT `fk_contact_user1`
  FOREIGN KEY (`creator`)
  REFERENCES `test1`.`user` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE,
  CONSTRAINT `fk_contact_user2`
  FOREIGN KEY (`updater`)
  REFERENCES `test1`.`user` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE)
  ENGINE = InnoDB
  COMMENT = 'Контактные лица';


-- -----------------------------------------------------
-- Table `test1`.`dictionary_contact__position`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `test1`.`dictionary_contact__position`;

CREATE TABLE IF NOT EXISTS `test1`.`dictionary_contact__position` (
  `id`     INT         NOT NULL AUTO_INCREMENT,
  `name`   VARCHAR(45) NOT NULL,
  `active` TINYINT(1)  NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`))
  COMMENT = 'Словарь - Должности контактов';


-- -----------------------------------------------------
-- Table `test1`.`relation_customer__contact`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `test1`.`relation_customer__contact`;

CREATE TABLE IF NOT EXISTS `test1`.`relation_customer__contact` (
  `id`       INT       NOT NULL AUTO_INCREMENT,
  `position` INT       NULL
  COMMENT 'Должность',
  `created`  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
  COMMENT 'Создан',
  `contact`  INT       NOT NULL
  COMMENT 'Контакт',
  `customer` INT       NOT NULL
  COMMENT 'Клиент',
  PRIMARY KEY (`id`, `contact`, `customer`),
  INDEX `fk_relation_customer__contact_contact1_idx` (`contact` ASC),
  INDEX `fk_relation_customer__contact_customer1_idx` (`customer` ASC),
  INDEX `fk_relation_customer__contact_position_idx` (`position` ASC),
  CONSTRAINT `fk_relation_customer__contact_contact1`
  FOREIGN KEY (`contact`)
  REFERENCES `test1`.`contact` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_relation_customer__contact_customer1`
  FOREIGN KEY (`customer`)
  REFERENCES `test1`.`customer` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_relation_customer__contact_position`
  FOREIGN KEY (`position`)
  REFERENCES `test1`.`dictionary_contact__position` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE)
  ENGINE = InnoDB
  COMMENT = 'Связь - Контакт -- Клиент';


-- -----------------------------------------------------
-- Table `test1`.`dictionary_opf`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `test1`.`dictionary_opf`;

CREATE TABLE IF NOT EXISTS `test1`.`dictionary_opf` (
  `id`           INT          NOT NULL AUTO_INCREMENT COMMENT 'Идентификатор организационно правовой формы',
  `name`         VARCHAR(255) NOT NULL
  COMMENT 'Название',
  `abbreviation` VARCHAR(20)  NOT NULL
  COMMENT 'Сокращение',
  `active`       TINYINT(1)   NOT NULL DEFAULT 1
  COMMENT 'Активность',
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC))
  ENGINE = InnoDB
  COMMENT = 'Словарь - Организационно правовые формы';


-- -----------------------------------------------------
-- Table `test1`.`requisites`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `test1`.`requisites`;

CREATE TABLE IF NOT EXISTS `test1`.`requisites` (
  `id`             INT          NOT NULL AUTO_INCREMENT COMMENT 'Идентификатор реквизита',
  `name`           VARCHAR(250) NOT NULL
  COMMENT 'Название',
  `opf`            INT          NOT NULL
  COMMENT 'Организационно-правовая форма',
  `inn`            INT          NULL
  COMMENT 'ИНН',
  `kpp`            INT          NULL DEFAULT NULL
  COMMENT 'КПП',
  `ogrn`           INT          NULL DEFAULT NULL
  COMMENT 'ОГРН',
  `customer`       INT          NOT NULL
  COMMENT 'Клиент',
  `created`        TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP
  COMMENT 'Создан',
  `updated`        TIMESTAMP    NULL DEFAULT NULL
  COMMENT 'Обновлен',
  `creator`        INT          NULL DEFAULT NULL
  COMMENT 'Кто создал',
  `updater`        INT          NULL DEFAULT NULL
  COMMENT 'Кто обновил',
  `director`       INT          NULL DEFAULT NULL
  COMMENT 'Директор',
  `actual_address` VARCHAR(250) NULL DEFAULT NULL
  COMMENT 'Реальный адрес',
  `legal_address`  VARCHAR(250) NULL DEFAULT NULL
  COMMENT 'Юридический адрес',
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC),
  INDEX `inn_INDEX` (`inn` ASC),
  INDEX `fk_requisites_customer1_idx` (`customer` ASC),
  INDEX `fk_requisites_user1_idx` (`creator` ASC),
  INDEX `fk_requisites_user2_idx` (`updater` ASC),
  INDEX `fk_requisites_contact1_idx` (`director` ASC),
  INDEX `fk_requisites_dictionary_opf1_idx` (`opf` ASC),
  CONSTRAINT `fk_requisites_customer1`
  FOREIGN KEY (`customer`)
  REFERENCES `test1`.`customer` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_requisites_user1`
  FOREIGN KEY (`creator`)
  REFERENCES `test1`.`user` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE,
  CONSTRAINT `fk_requisites_user2`
  FOREIGN KEY (`updater`)
  REFERENCES `test1`.`user` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE,
  CONSTRAINT `fk_requisites_contact1`
  FOREIGN KEY (`director`)
  REFERENCES `test1`.`contact` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE,
  CONSTRAINT `fk_requisites_dictionary_opf1`
  FOREIGN KEY (`opf`)
  REFERENCES `test1`.`dictionary_opf` (`id`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE)
  ENGINE = InnoDB
  COMMENT = 'Реквизиты клиента';


-- -----------------------------------------------------
-- Table `test1`.`dictionary_bank`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `test1`.`dictionary_bank`;

CREATE TABLE IF NOT EXISTS `test1`.`dictionary_bank` (
  `bic`    INT          NOT NULL,
  `name`   VARCHAR(255) NOT NULL,
  `active` TINYINT(1)   NOT NULL DEFAULT 1,
  PRIMARY KEY (`bic`),
  UNIQUE INDEX `id_UNIQUE` (`bic` ASC))
  ENGINE = InnoDB
  COMMENT = 'Словарь - Банки';


-- -----------------------------------------------------
-- Table `test1`.`requisites__bank_accounts`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `test1`.`requisites__bank_accounts`;

CREATE TABLE IF NOT EXISTS `test1`.`requisites__bank_accounts` (
  `id`                    INT          NOT NULL AUTO_INCREMENT COMMENT 'Идентификатор банковского реквизита',
  `currency`              VARCHAR(5)   NOT NULL DEFAULT 'RUB'
  COMMENT 'Валюта',
  `bik`                   INT          NOT NULL
  COMMENT 'БИК',
  `bank_name`             VARCHAR(255) NOT NULL,
  `bank_account`          VARCHAR(250) NOT NULL
  COMMENT 'Номер счета',
  `correspondent_account` VARCHAR(250) NOT NULL
  COMMENT 'Корреспондентский счет',
  `requisite`             INT          NOT NULL
  COMMENT 'Идентификатор реквизита',
  `created`               TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP
  COMMENT 'Создан',
  `updated`               TIMESTAMP    NULL DEFAULT NULL
  COMMENT 'Обновлен',
  `creator`               INT          NULL DEFAULT NULL
  COMMENT 'Кто создал',
  `updater`               INT          NULL DEFAULT NULL
  COMMENT 'Кто обновил',
  PRIMARY KEY (`id`),
  INDEX `requisite_INDEX` (`requisite` ASC),
  INDEX `fk_requisites__bank_accounts_user1_idx` (`creator` ASC),
  INDEX `fk_requisites__bank_accounts_dictionary_bank1_idx` (`bik` ASC),
  CONSTRAINT `fk_requisites__bank_accounts_requisites1`
  FOREIGN KEY (`requisite`)
  REFERENCES `test1`.`requisites` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_requisites__bank_accounts_user1`
  FOREIGN KEY (`creator`)
  REFERENCES `test1`.`user` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE,
  CONSTRAINT `fk_requisites__bank_accounts_dictionary_bank1`
  FOREIGN KEY (`bik`)
  REFERENCES `test1`.`dictionary_bank` (`bic`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
  ENGINE = InnoDB
  COMMENT = 'Банковские реквизиты';


-- -----------------------------------------------------
-- Table `test1`.`dictionary_message__type`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `test1`.`dictionary_message__type`;

CREATE TABLE IF NOT EXISTS `test1`.`dictionary_message__type` (
  `id`     INT         NOT NULL AUTO_INCREMENT,
  `name`   VARCHAR(45) NOT NULL,
  `active` TINYINT(1)  NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`))
  ENGINE = InnoDB
  COMMENT = 'Словарь - Типы сообщений';


-- -----------------------------------------------------
-- Table `test1`.`message`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `test1`.`message`;

CREATE TABLE IF NOT EXISTS `test1`.`message` (
  `id`        INT        NOT NULL AUTO_INCREMENT,
  `to_user`   INT        NOT NULL
  COMMENT 'Кому',
  `from_user` INT        NULL
  COMMENT 'От кого',
  `type`      INT        NULL
  COMMENT 'Тип сообщения',
  `subject`   TEXT       NULL
  COMMENT 'Тема',
  `message`   TEXT       NULL
  COMMENT 'Сообщение',
  `is_read`   TINYINT(1) NULL
  COMMENT 'Прочитано',
  `created`   TIMESTAMP  NOT NULL DEFAULT CURRENT_TIMESTAMP
  COMMENT 'Создано',
  PRIMARY KEY (`id`),
  INDEX `message_user_fk_idx` (`to_user` ASC),
  INDEX `message_type_fk_idx` (`type` ASC),
  INDEX `message_user_from_fk_idx` (`from_user` ASC),
  CONSTRAINT `message_user_fk`
  FOREIGN KEY (`to_user`)
  REFERENCES `test1`.`user` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `message_type_fk`
  FOREIGN KEY (`type`)
  REFERENCES `test1`.`dictionary_message__type` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE,
  CONSTRAINT `message_user_from_fk`
  FOREIGN KEY (`from_user`)
  REFERENCES `test1`.`user` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE)
  ENGINE = InnoDB
  COMMENT = 'Сообщение';


-- -----------------------------------------------------
-- Table `test1`.`dictionary_department`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `test1`.`dictionary_department`;

CREATE TABLE IF NOT EXISTS `test1`.`dictionary_department` (
  `id`        INT          NOT NULL AUTO_INCREMENT COMMENT 'Идентификатор подразделения',
  `parent_id` INT          NULL
  COMMENT 'Вышестоящее подразделение',
  `title`     VARCHAR(255) NOT NULL
  COMMENT 'Название',
  `head_id`   INT          NULL
  COMMENT 'Глава подразделения',
  PRIMARY KEY (`id`))
  ENGINE = InnoDB
  COMMENT = 'Словарь - структура подразделений';


-- -----------------------------------------------------
-- Table `test1`.`dictionary_user_position`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `test1`.`dictionary_user_position`;

CREATE TABLE IF NOT EXISTS `test1`.`dictionary_user_position` (
  `id`     INT          NOT NULL AUTO_INCREMENT COMMENT 'Идентификатор',
  `name`   VARCHAR(100) NOT NULL
  COMMENT 'Название должности',
  `active` TINYINT(1)   NOT NULL DEFAULT 1
  COMMENT 'Активность',
  PRIMARY KEY (`id`))
  ENGINE = InnoDB
  COMMENT = 'Словарь - Должности';


-- -----------------------------------------------------
-- Table `test1`.`main_menu`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `test1`.`main_menu`;

CREATE TABLE IF NOT EXISTS `test1`.`main_menu` (
  `id`     INT          NOT NULL AUTO_INCREMENT COMMENT 'Идентификатор элемента меню',
  `parent` INT          NULL
  COMMENT 'Родительский элемент',
  `label`  VARCHAR(100) NOT NULL DEFAULT 'Пункт меню'
  COMMENT 'Текст',
  `rule`   VARCHAR(100) NULL
  COMMENT 'Ограничивающее правило',
  `action` VARCHAR(100) NULL
  COMMENT 'Действие',
  `sort`   INT          NOT NULL DEFAULT 0
  COMMENT 'Сортировка',
  `active` TINYINT(1)   NOT NULL DEFAULT TRUE
  COMMENT 'Активен ли элемент',
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC))
  ENGINE = InnoDB
  COMMENT = 'Главное меню приложения';


-- -----------------------------------------------------
-- Table `test1`.`dictionary_log_customer__type`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `test1`.`dictionary_log_customer__type`;

CREATE TABLE IF NOT EXISTS `test1`.`dictionary_log_customer__type` (
  `id`     INT          NOT NULL
  COMMENT 'Идентификатор записи',
  `name`   VARCHAR(255) NOT NULL
  COMMENT 'Имя типа',
  `active` TINYINT(1)   NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC),
  UNIQUE INDEX `name_UNIQUE` (`name` ASC))
  ENGINE = InnoDB
  COMMENT = 'Словарь - Типы событий по клиенту';


-- -----------------------------------------------------
-- Table `test1`.`dictionary_task__type`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `test1`.`dictionary_task__type`;

CREATE TABLE IF NOT EXISTS `test1`.`dictionary_task__type` (
  `id`     INT         NOT NULL AUTO_INCREMENT COMMENT 'Идентификатор',
  `name`   VARCHAR(45) NOT NULL
  COMMENT 'Тип задачи',
  `active` TINYINT(1)  NOT NULL DEFAULT 1
  COMMENT 'Активность',
  PRIMARY KEY (`id`))
  ENGINE = InnoDB
  COMMENT = 'Словарь - Тип задачи';


-- -----------------------------------------------------
-- Table `test1`.`dictionary_task__goal`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `test1`.`dictionary_task__goal`;

CREATE TABLE IF NOT EXISTS `test1`.`dictionary_task__goal` (
  `id`     INT          NOT NULL AUTO_INCREMENT,
  `name`   VARCHAR(255) NOT NULL,
  `active` TINYINT(1)   NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`))
  ENGINE = InnoDB
  COMMENT = 'Словарь - Цели задач';


-- -----------------------------------------------------
-- Table `test1`.`dictionary_task__status`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `test1`.`dictionary_task__status`;

CREATE TABLE IF NOT EXISTS `test1`.`dictionary_task__status` (
  `id`     INT         NOT NULL AUTO_INCREMENT COMMENT 'Идентификатор',
  `name`   VARCHAR(45) NOT NULL
  COMMENT 'Статус',
  `active` TINYINT(1)  NOT NULL DEFAULT 1
  COMMENT 'Активность',
  PRIMARY KEY (`id`))
  ENGINE = InnoDB
  COMMENT = 'Словарь - Статусы задач';


-- -----------------------------------------------------
-- Table `test1`.`task`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `test1`.`task`;

CREATE TABLE IF NOT EXISTS `test1`.`task` (
  `id`          INT          NOT NULL AUTO_INCREMENT COMMENT 'Идентификатор задачи',
  `user`        INT          NULL
  COMMENT 'Исполнитель',
  `customer`    INT(11)      NULL
  COMMENT 'Клиент',
  `contact`     INT(11)      NULL
  COMMENT 'Контакт',
  `type`        INT(11)      NULL
  COMMENT 'Тип',
  `status`      INT(11)      NULL
  COMMENT 'Статус',
  `date_start`  DATE         NULL
  COMMENT 'Дата',
  `time_start`  TIME         NULL
  COMMENT 'Время',
  `goal`        INT(11)      NULL
  COMMENT 'Цель',
  `custom_goal` VARCHAR(255) NULL
  COMMENT 'Своя цель',
  `achievement` TINYINT(1)   NULL DEFAULT 0
  COMMENT 'Цель достигнута',
  `result`      TEXT         NULL
  COMMENT 'Результат',
  `note`        TEXT         NULL
  COMMENT 'Заметка',
  `created`     TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP
  COMMENT 'Создана',
  `updated`     TIMESTAMP    NULL
  COMMENT 'Обновлена',
  `creator`     INT(11)      NULL
  COMMENT 'Создатель',
  `updater`     INT(11)      NULL
  COMMENT 'Кто обновил',
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC),
  INDEX `task_client_fk_idx` (`id` ASC, `customer` ASC),
  INDEX `task_contact_fk_idx` (`id` ASC, `contact` ASC),
  INDEX `task_user_fk_idx` (`user` ASC, `creator` ASC, `updater` ASC),
  INDEX `task_type_fk_idx` (`type` ASC),
  INDEX `task_goal_fk_idx` (`goal` ASC),
  INDEX `task_status_fk_idx` (`status` ASC),
  CONSTRAINT `task_customer_fk`
  FOREIGN KEY (`id`, `customer`)
  REFERENCES `test1`.`customer` (`id`, `id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `task_contact_fk`
  FOREIGN KEY (`id`, `contact`)
  REFERENCES `test1`.`contact` (`id`, `id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE,
  CONSTRAINT `task_user_fk`
  FOREIGN KEY (`user`, `creator`, `updater`)
  REFERENCES `test1`.`user` (`id`, `id`, `id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE,
  CONSTRAINT `task_type_fk`
  FOREIGN KEY (`type`)
  REFERENCES `test1`.`dictionary_task__type` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE,
  CONSTRAINT `task_goal_fk`
  FOREIGN KEY (`goal`)
  REFERENCES `test1`.`dictionary_task__goal` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE,
  CONSTRAINT `task_status_fk`
  FOREIGN KEY (`status`)
  REFERENCES `test1`.`dictionary_task__status` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE)
  ENGINE = InnoDB
  COMMENT = 'Задачи';


-- -----------------------------------------------------
-- Table `test1`.`log_customer`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `test1`.`log_customer`;

CREATE TABLE IF NOT EXISTS `test1`.`log_customer` (
  `id`       INT       NOT NULL AUTO_INCREMENT COMMENT 'Идентификатор события',
  `type`     INT       NOT NULL
  COMMENT 'Тип события',
  `user`     INT       NULL
  COMMENT 'Пользователь',
  `customer` INT       NOT NULL
  COMMENT 'Клиент',
  `task`     INT       NULL,
  `message`  TEXT      NOT NULL
  COMMENT 'Сообщение',
  `created`  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
  COMMENT 'Произошло',
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC),
  INDEX `type_INDEX` (`type` ASC),
  INDEX `customer_INDEX` (`customer` ASC),
  INDEX `user_INDEX` (`user` ASC),
  INDEX `created_INDEX` (`created` ASC),
  INDEX `fk_task_log_customer_idx` (`task` ASC),
  CONSTRAINT `fk_log_customer_customer1`
  FOREIGN KEY (`customer`)
  REFERENCES `test1`.`customer` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_log_customer_user1`
  FOREIGN KEY (`user`)
  REFERENCES `test1`.`user` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE,
  CONSTRAINT `fk_log_customer_dictionary_log_customer__type1`
  FOREIGN KEY (`type`)
  REFERENCES `test1`.`dictionary_log_customer__type` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_task_log_customer`
  FOREIGN KEY (`task`)
  REFERENCES `test1`.`task` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE)
  ENGINE = InnoDB
  COMMENT = 'История - События по клиенту';


-- -----------------------------------------------------
-- Table `test1`.`auth_item`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `test1`.`auth_item`;

CREATE TABLE IF NOT EXISTS `test1`.`auth_item` (
  `name`        VARCHAR(255) NOT NULL
  COMMENT 'Название элемента авторизации',
  `type`        INT          NOT NULL
  COMMENT 'Тип',
  `description` TEXT         NULL
  COMMENT 'Описание',
  `bizrule`     TEXT         NULL DEFAULT NULL
  COMMENT 'Бизнес-правило',
  `data`        TEXT         NULL DEFAULT NULL
  COMMENT 'Данные',
  PRIMARY KEY (`name`))
  ENGINE = InnoDB
  COMMENT = 'Элементы авторизации';


-- -----------------------------------------------------
-- Table `test1`.`auth_item_child`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `test1`.`auth_item_child`;

CREATE TABLE IF NOT EXISTS `test1`.`auth_item_child` (
  `parent` VARCHAR(255) NOT NULL
  COMMENT 'Родитель',
  `child`  VARCHAR(255) NOT NULL
  COMMENT 'Ребенок',
  PRIMARY KEY (`parent`, `child`),
  INDEX `fk_auth_item_hierarchy_child` (`child` ASC),
  CONSTRAINT `fk_auth_item_hierarchy_parent`
  FOREIGN KEY (`parent`)
  REFERENCES `test1`.`auth_item` (`name`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_auth_item_hierarchy_child`
  FOREIGN KEY (`child`)
  REFERENCES `test1`.`auth_item` (`name`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
  ENGINE = InnoDB
  COMMENT = 'Иерархия элементов авторизации';


-- -----------------------------------------------------
-- Table `test1`.`auth_assignment`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `test1`.`auth_assignment`;

CREATE TABLE IF NOT EXISTS `test1`.`auth_assignment` (
  `itemname` VARCHAR(255) NOT NULL
  COMMENT 'Название элемента авторизации',
  `userid`   INT          NOT NULL
  COMMENT 'Пользователь',
  `bizrule`  TEXT         NULL
  COMMENT 'Бизнес-правило',
  `data`     TEXT         NULL
  COMMENT 'ХЗ',
  PRIMARY KEY (`itemname`, `userid`),
  INDEX `fk_assignment__user_idx` (`userid` ASC),
  CONSTRAINT `fk_assignment__auth_item`
  FOREIGN KEY (`itemname`)
  REFERENCES `test1`.`auth_item` (`name`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_assignment__user`
  FOREIGN KEY (`userid`)
  REFERENCES `test1`.`user` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
  ENGINE = InnoDB
  COMMENT = 'Назначение ролей пользователям';

SET SQL_MODE = '';
GRANT USAGE ON *.* TO test1;
DROP USER test1;
SET SQL_MODE = 'TRADITIONAL,ALLOW_INVALID_DATES';
CREATE USER 'test1'
  IDENTIFIED BY 'test1';

GRANT ALL ON `test1`.* TO 'test1';

SET SQL_MODE = @OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS = @OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS = @OLD_UNIQUE_CHECKS;

-- -----------------------------------------------------
-- Data for table `test1`.`dictionary_user_status`
-- -----------------------------------------------------
START TRANSACTION;
USE `test1`;
INSERT INTO `test1`.`dictionary_user_status` (`id`, `name`, `active`) VALUES (1, 'Онлайн', NULL);
INSERT INTO `test1`.`dictionary_user_status` (`id`, `name`, `active`) VALUES (2, 'Не в сети', NULL);
INSERT INTO `test1`.`dictionary_user_status` (`id`, `name`, `active`) VALUES (3, 'Уволен', NULL);
INSERT INTO `test1`.`dictionary_user_status` (`id`, `name`, `active`) VALUES (4, 'Заблокирован', NULL);
INSERT INTO `test1`.`dictionary_user_status` (`id`, `name`, `active`) VALUES (5, 'Отошел', NULL);
INSERT INTO `test1`.`dictionary_user_status` (`id`, `name`, `active`) VALUES (6, 'Не беспокоить', NULL);
INSERT INTO `test1`.`dictionary_user_status` (`id`, `name`, `active`) VALUES (7, 'Спит', NULL);

COMMIT;


-- -----------------------------------------------------
-- Data for table `test1`.`user`
-- -----------------------------------------------------
START TRANSACTION;
USE `test1`;
INSERT INTO `test1`.`user` (`id`, `login`, `password`, `email`, `phone`, `created`, `updated`, `lastname`, `firstname`, `surename`, `gender`, `role`, `department`, `birth_date`, `passport_serial`, `passport_number`, `passport_date`, `passport_department_code`, `birth_place`, `labor_contract_number`, `labor_contract_date`, `status`, `last_ip`, `last_mac_address`, `allowed`, `forbidden`, `position`, `avatar`, `photo`) VALUES (1, 'bot1', '96e79218965eb72c92a549dd5a330112', 'bot1@gmail.com', '+79132229915', NULL, NULL, 'Ботов', 'Бот1', 'Ботович', 1, 'user', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `test1`.`user` (`id`, `login`, `password`, `email`, `phone`, `created`, `updated`, `lastname`, `firstname`, `surename`, `gender`, `role`, `department`, `birth_date`, `passport_serial`, `passport_number`, `passport_date`, `passport_department_code`, `birth_place`, `labor_contract_number`, `labor_contract_date`, `status`, `last_ip`, `last_mac_address`, `allowed`, `forbidden`, `position`, `avatar`, `photo`) VALUES (2, 'bot2', '96e79218965eb72c92a549dd5a330112', 'bot2@gmail.com', '+79132229916', NULL, NULL, 'Ботов', 'Бот2', 'Ботович', 1, 'user', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `test1`.`user` (`id`, `login`, `password`, `email`, `phone`, `created`, `updated`, `lastname`, `firstname`, `surename`, `gender`, `role`, `department`, `birth_date`, `passport_serial`, `passport_number`, `passport_date`, `passport_department_code`, `birth_place`, `labor_contract_number`, `labor_contract_date`, `status`, `last_ip`, `last_mac_address`, `allowed`, `forbidden`, `position`, `avatar`, `photo`) VALUES (3, 'bot3', '96e79218965eb72c92a549dd5a330112', 'bot3@gmail.com', '+79132229917', NULL, NULL, 'Ботов', 'Бот3', 'Ботович', 1, 'user', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `test1`.`user` (`id`, `login`, `password`, `email`, `phone`, `created`, `updated`, `lastname`, `firstname`, `surename`, `gender`, `role`, `department`, `birth_date`, `passport_serial`, `passport_number`, `passport_date`, `passport_department_code`, `birth_place`, `labor_contract_number`, `labor_contract_date`, `status`, `last_ip`, `last_mac_address`, `allowed`, `forbidden`, `position`, `avatar`, `photo`) VALUES (4, 'bot4', '96e79218965eb72c92a549dd5a330112', 'bot4@gmail.com', '+79132229918', NULL, NULL, 'Ботов', 'Бот4', 'Ботович', 1, 'user', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `test1`.`user` (`id`, `login`, `password`, `email`, `phone`, `created`, `updated`, `lastname`, `firstname`, `surename`, `gender`, `role`, `department`, `birth_date`, `passport_serial`, `passport_number`, `passport_date`, `passport_department_code`, `birth_place`, `labor_contract_number`, `labor_contract_date`, `status`, `last_ip`, `last_mac_address`, `allowed`, `forbidden`, `position`, `avatar`, `photo`) VALUES (5, 'bot5', '96e79218965eb72c92a549dd5a330112', 'bot5@gmail.com', '+79132229919', NULL, NULL, 'Ботов', 'Бот5', 'Ботович', 1, 'user', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `test1`.`user` (`id`, `login`, `password`, `email`, `phone`, `created`, `updated`, `lastname`, `firstname`, `surename`, `gender`, `role`, `department`, `birth_date`, `passport_serial`, `passport_number`, `passport_date`, `passport_department_code`, `birth_place`, `labor_contract_number`, `labor_contract_date`, `status`, `last_ip`, `last_mac_address`, `allowed`, `forbidden`, `position`, `avatar`, `photo`) VALUES (6, 'bot6', '96e79218965eb72c92a549dd5a330112', 'bot6@gmail.com', '+79132229911', NULL, NULL, 'Ботов', 'Бот6', 'Ботович', 1, 'user', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `test1`.`user` (`id`, `login`, `password`, `email`, `phone`, `created`, `updated`, `lastname`, `firstname`, `surename`, `gender`, `role`, `department`, `birth_date`, `passport_serial`, `passport_number`, `passport_date`, `passport_department_code`, `birth_place`, `labor_contract_number`, `labor_contract_date`, `status`, `last_ip`, `last_mac_address`, `allowed`, `forbidden`, `position`, `avatar`, `photo`) VALUES (7, 'admin', '96e79218965eb72c92a549dd5a330112', 'admin@gmail.com', '+79132229912', NULL, NULL, 'Админ', 'Админ', 'Админович', 1, 'admin', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

COMMIT;


-- -----------------------------------------------------
-- Data for table `test1`.`dictionary_user__history___events`
-- -----------------------------------------------------
START TRANSACTION;
USE `test1`;
INSERT INTO `test1`.`dictionary_user__history___events` (`id`, `name`, `active`) VALUES (1, 'Вход в систему', NULL);
INSERT INTO `test1`.`dictionary_user__history___events` (`id`, `name`, `active`) VALUES (2, 'Выход из системы', NULL);
INSERT INTO `test1`.`dictionary_user__history___events` (`id`, `name`, `active`) VALUES (3, 'Смена пароля', NULL);
INSERT INTO `test1`.`dictionary_user__history___events` (`id`, `name`, `active`) VALUES (4, 'Смена электронной почты', NULL);
INSERT INTO `test1`.`dictionary_user__history___events` (`id`, `name`, `active`) VALUES (5, 'Смена телефона', NULL);
INSERT INTO `test1`.`dictionary_user__history___events` (`id`, `name`, `active`) VALUES (6, 'Смена IP адреса', NULL);
INSERT INTO `test1`.`dictionary_user__history___events` (`id`, `name`, `active`) VALUES (7, 'Смена MAC адреса', NULL);
INSERT INTO `test1`.`dictionary_user__history___events` (`id`, `name`, `active`) VALUES (8, 'Смена пола', NULL);
INSERT INTO `test1`.`dictionary_user__history___events` (`id`, `name`, `active`) VALUES (9, 'Увольнение', NULL);
INSERT INTO `test1`.`dictionary_user__history___events` (`id`, `name`, `active`) VALUES (10, 'Был поздравлен с днем рождения', NULL);
INSERT INTO `test1`.`dictionary_user__history___events` (`id`, `name`, `active`) VALUES (11, 'Получил сообщение', NULL);
INSERT INTO `test1`.`dictionary_user__history___events` (`id`, `name`, `active`) VALUES (12, 'Получил по щам', NULL);
INSERT INTO `test1`.`dictionary_user__history___events` (`id`, `name`, `active`) VALUES (13, 'Создал клиента', NULL);
INSERT INTO `test1`.`dictionary_user__history___events` (`id`, `name`, `active`) VALUES (14, 'Привлек нового клиента', NULL);
INSERT INTO `test1`.`dictionary_user__history___events` (`id`, `name`, `active`) VALUES (15, 'Сделал продажу', NULL);
INSERT INTO `test1`.`dictionary_user__history___events` (`id`, `name`, `active`) VALUES (16, 'Зашел в аналитику', NULL);
INSERT INTO `test1`.`dictionary_user__history___events` (`id`, `name`, `active`) VALUES (17, 'Отошел', NULL);
INSERT INTO `test1`.`dictionary_user__history___events` (`id`, `name`, `active`) VALUES (18, 'Зарегистрирована длительная неактивность', NULL);
INSERT INTO `test1`.`dictionary_user__history___events` (`id`, `name`, `active`) VALUES (19, 'Зашел в нерабочее время', NULL);
INSERT INTO `test1`.`dictionary_user__history___events` (`id`, `name`, `active`) VALUES (20, 'Опоздал на работу', NULL);
INSERT INTO `test1`.`dictionary_user__history___events` (`id`, `name`, `active`) VALUES (21, 'Ушел раньше', NULL);
INSERT INTO `test1`.`dictionary_user__history___events` (`id`, `name`, `active`) VALUES (22, 'Принял звонок', NULL);

COMMIT;


-- -----------------------------------------------------
-- Data for table `test1`.`dictionary_opf`
-- -----------------------------------------------------
START TRANSACTION;
USE `test1`;
INSERT INTO `test1`.`dictionary_opf` (`id`, `name`, `abbreviation`, `active`) VALUES (1, 'Общество с ограниченной ответственностью', 'ООО', NULL);
INSERT INTO `test1`.`dictionary_opf` (`id`, `name`, `abbreviation`, `active`) VALUES (2, 'Индивидуальный предприниматель', 'ИП', NULL);
INSERT INTO `test1`.`dictionary_opf` (`id`, `name`, `abbreviation`, `active`) VALUES (3, 'Частное лицо', 'Частное лицо', NULL);
INSERT INTO `test1`.`dictionary_opf` (`id`, `name`, `abbreviation`, `active`) VALUES (4, ' Полное товарищество', 'ПТ', NULL);
INSERT INTO `test1`.`dictionary_opf` (`id`, `name`, `abbreviation`, `active`) VALUES (5, 'Товарищество на вере', 'ТВ', NULL);
INSERT INTO `test1`.`dictionary_opf` (`id`, `name`, `abbreviation`, `active`) VALUES (6, 'Открытое акционерное общество', 'ОАО', NULL);
INSERT INTO `test1`.`dictionary_opf` (`id`, `name`, `abbreviation`, `active`) VALUES (7, 'Частное торговое унитарное предприятие', 'ЧТУП', NULL);
INSERT INTO `test1`.`dictionary_opf` (`id`, `name`, `abbreviation`, `active`) VALUES (8, 'Общество с дополнительной ответственностью', 'ОДО', NULL);
INSERT INTO `test1`.`dictionary_opf` (`id`, `name`, `abbreviation`, `active`) VALUES (9, 'Строительно-монтажное управление', 'СМУ', NULL);
INSERT INTO `test1`.`dictionary_opf` (`id`, `name`, `abbreviation`, `active`) VALUES (10, 'Производственный кооператив', 'ПК', NULL);
INSERT INTO `test1`.`dictionary_opf` (`id`, `name`, `abbreviation`, `active`) VALUES (11, 'Унитарное предприятие', 'УП', NULL);
INSERT INTO `test1`.`dictionary_opf` (`id`, `name`, `abbreviation`, `active`) VALUES (0, 'Дбавить ОПФ', 'Добавить форму', NULL);

COMMIT;


-- -----------------------------------------------------
-- Data for table `test1`.`dictionary_bank`
-- -----------------------------------------------------
START TRANSACTION;
USE `test1`;
INSERT INTO `test1`.`dictionary_bank` (`bic`, `name`, `active`) VALUES (040147781, 'НАРОДНЫЙ ЗЕМЕЛЬНО-ПРОМЫШЛЕННЫЙ БАНК', NULL);
INSERT INTO `test1`.`dictionary_bank` (`bic`, `name`, `active`) VALUES (040173725, 'АЛТАЙБИЗНЕС-БАНК', NULL);

COMMIT;


-- -----------------------------------------------------
-- Data for table `test1`.`dictionary_message__type`
-- -----------------------------------------------------
START TRANSACTION;
USE `test1`;
INSERT INTO `test1`.`dictionary_message__type` (`id`, `name`, `active`) VALUES (1, 'Системное сообщение', 1);
INSERT INTO `test1`.`dictionary_message__type` (`id`, `name`, `active`) VALUES (2, 'Напоминание', 1);
INSERT INTO `test1`.`dictionary_message__type` (`id`, `name`, `active`) VALUES (3, 'Уведомление', 1);
INSERT INTO `test1`.`dictionary_message__type` (`id`, `name`, `active`) VALUES (4, 'Поздравление', 1);
INSERT INTO `test1`.`dictionary_message__type` (`id`, `name`, `active`) VALUES (5, 'Критика', 1);
INSERT INTO `test1`.`dictionary_message__type` (`id`, `name`, `active`) VALUES (6, 'Поощрение', 1);
INSERT INTO `test1`.`dictionary_message__type` (`id`, `name`, `active`) VALUES (7, 'Ошибка', 1);
INSERT INTO `test1`.`dictionary_message__type` (`id`, `name`, `active`) VALUES (8, 'Совет', 1);
INSERT INTO `test1`.`dictionary_message__type` (`id`, `name`, `active`) VALUES (9, 'Личное сообщение', 1);
INSERT INTO `test1`.`dictionary_message__type` (`id`, `name`, `active`) VALUES (10, 'Рассылка', 1);
INSERT INTO `test1`.`dictionary_message__type` (`id`, `name`, `active`) VALUES (11, 'Спам', 1);

COMMIT;


-- -----------------------------------------------------
-- Data for table `test1`.`main_menu`
-- -----------------------------------------------------
START TRANSACTION;
USE `test1`;
INSERT INTO `test1`.`main_menu` (`id`, `parent`, `label`, `rule`, `action`, `sort`, `active`) VALUES (1, NULL, 'Клиенты', '', 'client/index', 1, 1);
INSERT INTO `test1`.`main_menu` (`id`, `parent`, `label`, `rule`, `action`, `sort`, `active`) VALUES (2, NULL, 'Задачи', NULL, 'task/index', 2, 1);
INSERT INTO `test1`.`main_menu` (`id`, `parent`, `label`, `rule`, `action`, `sort`, `active`) VALUES (3, NULL, 'История', NULL, 'history/index', 3, 1);
INSERT INTO `test1`.`main_menu` (`id`, `parent`, `label`, `rule`, `action`, `sort`, `active`) VALUES (4, NULL, 'Отчеты', NULL, 'report/index', 4, 1);
INSERT INTO `test1`.`main_menu` (`id`, `parent`, `label`, `rule`, `action`, `sort`, `active`) VALUES (5, NULL, 'База знаний', NULL, 'knowlege/index', 5, 1);
INSERT INTO `test1`.`main_menu` (`id`, `parent`, `label`, `rule`, `action`, `sort`, `active`) VALUES (6, NULL, 'Календарь', NULL, 'task/calendar', 6, 1);

COMMIT;


-- -----------------------------------------------------
-- Data for table `test1`.`dictionary_log_customer__type`
-- -----------------------------------------------------
START TRANSACTION;
USE `test1`;
INSERT INTO `test1`.`dictionary_log_customer__type` (`id`, `name`, `active`) VALUES (1, 'Добавление клиента', NULL);
INSERT INTO `test1`.`dictionary_log_customer__type` (`id`, `name`, `active`) VALUES (2, 'Удаление клиента', NULL);
INSERT INTO `test1`.`dictionary_log_customer__type` (`id`, `name`, `active`) VALUES (3, 'Изменение информации по клиенту', NULL);
INSERT INTO `test1`.`dictionary_log_customer__type` (`id`, `name`, `active`) VALUES (4, 'Продажа', NULL);
INSERT INTO `test1`.`dictionary_log_customer__type` (`id`, `name`, `active`) VALUES (5, 'Звонок клиенту', NULL);
INSERT INTO `test1`.`dictionary_log_customer__type` (`id`, `name`, `active`) VALUES (6, 'Встреча', NULL);
INSERT INTO `test1`.`dictionary_log_customer__type` (`id`, `name`, `active`) VALUES (7, 'Звонок от клиента', NULL);
INSERT INTO `test1`.`dictionary_log_customer__type` (`id`, `name`, `active`) VALUES (8, 'Заявка от клиента', NULL);
INSERT INTO `test1`.`dictionary_log_customer__type` (`id`, `name`, `active`) VALUES (9, 'Смена статуса клиента', NULL);
INSERT INTO `test1`.`dictionary_log_customer__type` (`id`, `name`, `active`) VALUES (10, 'Попадание клиента в отчет', NULL);
INSERT INTO `test1`.`dictionary_log_customer__type` (`id`, `name`, `active`) VALUES (11, 'Формирование отчета по клиенту', NULL);
INSERT INTO `test1`.`dictionary_log_customer__type` (`id`, `name`, `active`) VALUES (12, 'Попытка зайти не в своего клиента', NULL);
INSERT INTO `test1`.`dictionary_log_customer__type` (`id`, `name`, `active`) VALUES (13, 'Просроченная задача по клиенту', NULL);
INSERT INTO `test1`.`dictionary_log_customer__type` (`id`, `name`, `active`) VALUES (14, 'Слишком долгая неактивность по клиенту', NULL);
INSERT INTO `test1`.`dictionary_log_customer__type` (`id`, `name`, `active`) VALUES (15, 'Смена ответсвенного по клиенту', NULL);
INSERT INTO `test1`.`dictionary_log_customer__type` (`id`, `name`, `active`) VALUES (16, 'Назначение ответственного по клиенту', NULL);
INSERT INTO `test1`.`dictionary_log_customer__type` (`id`, `name`, `active`) VALUES (17, 'Снятие ответсвенного по клиенту', NULL);

COMMIT;


-- -----------------------------------------------------
-- Data for table `test1`.`dictionary_task__type`
-- -----------------------------------------------------
START TRANSACTION;
USE `test1`;
INSERT INTO `test1`.`dictionary_task__type` (`id`, `name`, `active`) VALUES (1, 'Звонок', 1);
INSERT INTO `test1`.`dictionary_task__type` (`id`, `name`, `active`) VALUES (2, 'Email', 1);
INSERT INTO `test1`.`dictionary_task__type` (`id`, `name`, `active`) VALUES (3, 'Встреча', 1);
INSERT INTO `test1`.`dictionary_task__type` (`id`, `name`, `active`) VALUES (4, 'Командировка', 1);

COMMIT;


-- -----------------------------------------------------
-- Data for table `test1`.`dictionary_task__goal`
-- -----------------------------------------------------
START TRANSACTION;
USE `test1`;
INSERT INTO `test1`.`dictionary_task__goal` (`id`, `name`, `active`) VALUES (1, 'Договориться о встрече', 1);
INSERT INTO `test1`.`dictionary_task__goal` (`id`, `name`, `active`) VALUES (2, 'Рассказать о продукте', 1);
INSERT INTO `test1`.`dictionary_task__goal` (`id`, `name`, `active`) VALUES (3, 'Запросить контактные данные', 1);
INSERT INTO `test1`.`dictionary_task__goal` (`id`, `name`, `active`) VALUES (4, 'Продать продукт', 1);
INSERT INTO `test1`.`dictionary_task__goal` (`id`, `name`, `active`) VALUES (5, 'Провести презентацию', 1);
INSERT INTO `test1`.`dictionary_task__goal` (`id`, `name`, `active`) VALUES (6, 'Решить вопрос по документам', 1);
INSERT INTO `test1`.`dictionary_task__goal` (`id`, `name`, `active`) VALUES (7, 'Решить вопрос по оплате', 1);
INSERT INTO `test1`.`dictionary_task__goal` (`id`, `name`, `active`) VALUES (8, 'Подписать договор', 1);
INSERT INTO `test1`.`dictionary_task__goal` (`id`, `name`, `active`) VALUES (9, 'Напомнить что-нибудь', 1);
INSERT INTO `test1`.`dictionary_task__goal` (`id`, `name`, `active`) VALUES (10, 'Вы можете редактировать цели', 1);

COMMIT;


-- -----------------------------------------------------
-- Data for table `test1`.`dictionary_task__status`
-- -----------------------------------------------------
START TRANSACTION;
USE `test1`;
INSERT INTO `test1`.`dictionary_task__status` (`id`, `name`, `active`) VALUES (1, 'Новая', 1);
INSERT INTO `test1`.`dictionary_task__status` (`id`, `name`, `active`) VALUES (2, 'Закрыта', 1);
INSERT INTO `test1`.`dictionary_task__status` (`id`, `name`, `active`) VALUES (3, 'Просрочена', 1);
INSERT INTO `test1`.`dictionary_task__status` (`id`, `name`, `active`) VALUES (4, 'Перенесена на другой день', 1);
INSERT INTO `test1`.`dictionary_task__status` (`id`, `name`, `active`) VALUES (5, 'Перенесена на другое время', 1);
INSERT INTO `test1`.`dictionary_task__status` (`id`, `name`, `active`) VALUES (6, 'Запланирована', 1);

COMMIT;

USE `test1`;

DELIMITER $$

USE `test1`$$
DROP TRIGGER IF EXISTS `test1`.`user_AUPD` $$
USE `test1`$$
CREATE TRIGGER `user_AUPD` BEFORE UPDATE ON `user` FOR EACH ROW
  BEGIN

    SET NEW.updated = now();

  END;

-- Edit trigger body code below this line. Do not edit lines above  this one
$$


USE `test1`$$
DROP TRIGGER IF EXISTS `test1`.`customer_AUPD` $$
USE `test1`$$
CREATE TRIGGER `customer_AUPD` BEFORE UPDATE ON `customer` FOR EACH ROW
  BEGIN

    SET NEW.updated = now();

  END;
$$


USE `test1`$$
DROP TRIGGER IF EXISTS `test1`.`contact_AUPD` $$
USE `test1`$$
CREATE TRIGGER `contact_AUPD` BEFORE UPDATE ON `contact` FOR EACH ROW
  BEGIN

    SET NEW.updated = now();

  END;
-- Edit trigger body code below this line. Do not edit lines above this one
$$


USE `test1`$$
DROP TRIGGER IF EXISTS `test1`.`requisites_AUPD` $$
USE `test1`$$
CREATE TRIGGER `requisites_AUPD` BEFORE UPDATE ON `requisites` FOR EACH ROW
  BEGIN

    SET NEW.updated = now();

  END;
-- Edit trigger body code below this line. Do not edit lines above this one
$$


USE `test1`$$
DROP TRIGGER IF EXISTS `test1`.`requisites__bank_accounts_AUPD` $$
USE `test1`$$
CREATE TRIGGER `requisites__bank_accounts_AUPD` BEFORE UPDATE ON `requisites__bank_accounts` FOR EACH ROW
  BEGIN

    SET NEW.updated = now();

  END;
-- Edit trigger body code below this line. Do not edit lines above this one
$$


USE `test1`$$
DROP TRIGGER IF EXISTS `test1`.`task_updated` $$
USE `test1`$$
CREATE TRIGGER `task_updated` BEFORE UPDATE ON `task` FOR EACH ROW
  BEGIN

    SET NEW.updated = now();

  END;
-- Edit trigger body code below this line. Do not edit lines above this one

$$


DELIMITER ;

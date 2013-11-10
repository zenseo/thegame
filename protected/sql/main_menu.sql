-- -----------------------------------------------------
-- Table test1.main_menu
-- -----------------------------------------------------
DROP TABLE IF EXISTS test1.main_menu;

CREATE TABLE IF NOT EXISTS test1.main_menu (
  id     INT          NOT NULL AUTO_INCREMENT COMMENT 'Идентификатор элемента меню',
  parent INT          NULL COMMENT 'Родительский элемент',
  label  VARCHAR(100) NOT NULL DEFAULT 'Пункт меню' COMMENT 'Текст',
  rule   VARCHAR(100) NULL COMMENT 'Ограничивающее правило',
  action VARCHAR(100) NULL COMMENT 'Действие',
  sort   INT          NOT NULL DEFAULT 0 COMMENT 'Сортировка',
  active TINYINT (1) NOT NULL DEFAULT TRUE COMMENT 'Активен ли элемент',
  PRIMARY KEY (id),
  UNIQUE INDEX id_UNIQUE (id ASC))
  ENGINE = InnoDB
COMMENT = 'Главное меню приложения';
INSERT INTO test1.main_menu (id, parent, label, rule, action, sort, active) VALUES (1, NULL, 'Клиенты', '', 'client/index', 1, 1);
INSERT INTO test1.main_menu (id, parent, label, rule, action, sort, active) VALUES (2, NULL, 'Задачи', NULL, 'task/index', 2, 1);
INSERT INTO test1.main_menu (id, parent, label, rule, action, sort, active) VALUES (3, NULL, 'История', NULL, 'history/index', 3, 1);
INSERT INTO test1.main_menu (id, parent, label, rule, action, sort, active) VALUES (4, NULL, 'Отчеты', NULL, 'report/index', 4, 1);
INSERT INTO test1.main_menu (id, parent, label, rule, action, sort, active) VALUES (5, NULL, 'База знаний', NULL, 'knowlege/index', 5, 1);
INSERT INTO test1.main_menu (id, parent, label, rule, action, sort, active) VALUES (6, NULL, 'Календарь', NULL, 'task/calendar', 6, 1);
INSERT INTO test1.main_menu (id, parent, label, rule, action, sort, active) VALUES (7, NULL, 'Пользователи', NULL, 'user/index', 7, 1);
INSERT INTO test1.main_menu (id, parent, label, rule, action, sort, active) VALUES (8, NULL, 'Тестирование', NULL, 'site/test', 8, 1);



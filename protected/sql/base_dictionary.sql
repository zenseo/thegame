CREATE TABLE production_calendar (
  id            INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  calendar_date TIMESTAMP,
  year          INT,
  five_day_week INT DEFAULT 0,
  six_day_week  INT DEFAULT 0,
  calendar_days INT DEFAULT 0,
  type_day      INT
)
  ENGINE InnoDB;

CREATE TABLE production_calendar__day_type (
  id   INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(250)
)
  ENGINE InnoDB;
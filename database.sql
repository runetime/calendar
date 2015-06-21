CREATE DATABASE IF NOT EXISTS rtcalendar;

CREATE TABLE IF NOT EXISTS rtcalendar.radio_history (
   id INT NOT NULL AUTO_INCREMENT,
   PRIMARY KEY(id),
   `week`    INT(2) NOT NULL,
   `day`     INT(1) NOT NULL,
   `hour`    INT(2) NOT NULL,
   `dj_name` VARCHAR(100)
);

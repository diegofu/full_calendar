/*
 * Config/FullCalendar.sql
 * CakePHP Full Calendar Plugin
 *
 * Copyright (c) 2010 Silas Montgomery
 * http://silasmontgomery.com
 *
 * Licensed under MIT
 * http://www.opensource.org/licenses/mit-license.php
 */

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- --------------------------------------------------------

--
-- Table structure for table `events`
--


CREATE TABLE IF NOT EXISTS event_parents (
  id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(50) NOT NULL,
  recurring BOOLEAN NOT NULL,
  recurrence_type VARCHAR(10),
  start DATETIME NOT NULL,
  recur_end DATETIME,
  location VARCHAR(50),
  address VARCHAR(100),
  city VARCHAR(50),
  state VARCHAR(50),
  zip_code VARCHAR(20),
  detail TEXT
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `events` (
  id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  parent_id INT NOT NULL,
  start DATETIME NOT NULL,
  end DATETIME NOT NULL,
  
  FOREIGN KEY (parent_id) REFERENCES event_parents(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS tags (
  id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL

)ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS event_tags (
  event_parent_id INT NOT NULL,
  tag_id INT NOT NULL,
  FOREIGN KEY (event_parent_id) REFERENCES event_parents(id),
  FOREIGN KEY (tag_id) REFERENCES tags(id)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


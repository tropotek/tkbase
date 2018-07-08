


DROP TABLE IF EXISTS `subscriber`;
CREATE TABLE IF NOT EXISTS `subscriber` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL DEFAULT '',
  `email` varchar(128) NOT NULL DEFAULT '',
  `notes` TEXT,
  `active` TINYINT(1) NOT NULL DEFAULT 1,
  `del` TINYINT(1) NOT NULL DEFAULT 0,
  `modified` DATETIME NOT NULL,
  `created` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB;

DROP TABLE IF EXISTS `event`;
CREATE TABLE IF NOT EXISTS `event` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(128) NOT NULL DEFAULT '',
  `date_start` DATETIME NOT NULL,
  `date_end` DATETIME NOT NULL,
  `rsvp` TEXT,
  `description` TEXT,

  `street` VARCHAR(128) NOT NULL DEFAULT '',
  `city` VARCHAR(128) NOT NULL DEFAULT '',
  `state` VARCHAR(128) NOT NULL DEFAULT '',
  `postcode` VARCHAR(16) NOT NULL DEFAULT '',
  `country` VARCHAR(128) NOT NULL DEFAULT '',
  `address` TEXT,                                        -- The full google address: [no] [street], [city], [state], [country]
  `map_lat` DECIMAL(15,12) NOT NULL DEFAULT 0,
  `map_lng` DECIMAL(15,12) NOT NULL DEFAULT 0,
  `map_zoom` DECIMAL(4, 2) NOT NULL DEFAULT 14,

  `notes` TEXT,
  `active` TINYINT(1) NOT NULL DEFAULT 1,
  `del` TINYINT(1) NOT NULL DEFAULT 0,
  `modified` DATETIME NOT NULL,
  `created` DATETIME NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;






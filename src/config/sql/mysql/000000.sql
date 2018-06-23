

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL DEFAULT '',
  `username` varchar(255) NOT NULL DEFAULT '',
  `password` varchar(255) NOT NULL DEFAULT '',
  `role` varchar(255) NOT NULL DEFAULT '',
  `notes` TEXT,
  `last_login` TIMESTAMP,
  `active` TINYINT(1) NOT NULL DEFAULT 1,
  `hash` varchar(255) NOT NULL DEFAULT '',
  `del` TINYINT(1) NOT NULL DEFAULT 0,
  `modified` DATETIME NOT NULL,
  `created` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

INSERT INTO `user` (`id`, `name`, `email`, `username`, `password`, `role`, `active`, `hash`, `modified`, `created`)
VALUES
  (NULL, 'Administrator', 'admin@example.com', 'admin', MD5('password'), 'admin', 1, MD5('1:admin:admin@example.com'), NOW() , NOW()),
  (NULL, 'User 1', 'user@example.com', 'user1', MD5('password'), 'user', 1, MD5('2:user:user@example.com'), NOW() , NOW())
;






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


DROP TABLE IF EXISTS `mail_log`;
CREATE TABLE IF NOT EXISTS `mail_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `to` text,
  `from` text,
  `subject` text,
  `body` text,
  `hash` varchar(64) DEFAULT NULL,
  `notes` text,
  `del` TINYINT(1) NOT NULL DEFAULT 0,
  `created` DATETIME NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;





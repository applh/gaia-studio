-- warning: DROP TABLE will delete all data
DROP TABLE IF EXISTS `geocms`;

CREATE TABLE `geocms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `path` text DEFAULT NULL,
  `filename` text DEFAULT NULL,
  `code` mediumtext DEFAULT NULL,
  `url` text DEFAULT NULL,
  `title` text DEFAULT NULL,
  `content` mediumtext DEFAULT NULL,
  `media` text DEFAULT NULL,
  `template` text DEFAULT NULL,
  `cat` text DEFAULT NULL,
  `tags` text DEFAULT NULL,
  `created` text DEFAULT NULL,
  `updated` text DEFAULT NULL,
  `hash` text DEFAULT NULL,
  `x` double DEFAULT NULL,
  `y` double DEFAULT NULL,
  `z` double DEFAULT NULL,
  `t` double DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- type text is limited to 65535 bytes
-- type mediumtext is limited to 16777215 bytes
-- CREATE TABLE IF NOT EXISTS `geocms` 
-- (
--     id INTEGER PRIMARY KEY AUTO_INCREMENT,
--     path TEXT,
--     filename TEXT,
--     code MEDIUMTEXT,
--     url TEXT,
--     title TEXT,
--     content MEDIUMTEXT,
--     media TEXT,
--     template TEXT,
--     cat TEXT,
--     tags TEXT,
--     created TEXT,
--     updated TEXT,
--     hash TEXT,
--     x REAL,
--     y REAL,
--     z REAL,
--     t REAL
-- );

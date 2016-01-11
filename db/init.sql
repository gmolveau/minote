CREATE TABLE IF NOT EXISTS `note` (
  `id` varchar(20) NOT NULL,
  `content` text,
  `pwdEdit` varchar(20),
  `pwdView` varchar(20),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT charset=utf8;
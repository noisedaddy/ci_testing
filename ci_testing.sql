CREATE DATABASE `ci_testing` /*!40100 DEFAULT CHARACTER SET utf8 */;

CREATE TABLE `game` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `start_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `fk_winner_id` int(11) DEFAULT NULL,
  `status` varchar(45) DEFAULT NULL,
  `player_one_nick` varchar(45) DEFAULT NULL,
  `player_two_nick` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

CREATE TABLE `players` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `player_nick` varchar(45) DEFAULT NULL,
  `player_symbol` tinytext,
  `fk_game_id` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

CREATE TABLE `position` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pos_x` int(11) DEFAULT NULL,
  `pos_y` int(11) DEFAULT NULL,
  `fk_player_id` int(11) DEFAULT NULL,
  `fk_game_id` int(11) DEFAULT NULL,
  `win_field` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8;

CREATE TABLE `game` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `start_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `player_one_nick` varchar(45) DEFAULT NULL,
  `player_two_nick` varchar(45) DEFAULT NULL,
  `fk_winner_id` int(11) DEFAULT NULL,
  `status` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8;


CREATE TABLE `players` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `player_nick` varchar(45) DEFAULT NULL,
  `player_symbol` tinytext,
  `fk_game_id` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;


CREATE TABLE `position` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pos_x` int(11) DEFAULT NULL,
  `pos_y` int(11) DEFAULT NULL,
  `fk_player_id` int(11) DEFAULT NULL,
  `fk_game_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=62 DEFAULT CHARSET=utf8;

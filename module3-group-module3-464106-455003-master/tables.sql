users | CREATE TABLE `users` (
  `user_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `username` varchar(75) NOT NULL,
  `pwd` varchar(100) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8
---
stories | CREATE TABLE `stories` (
  `story_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` mediumint(8) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `body` longtext NOT NULL,
  `date` datetime NOT NULL,
  `link` text NOT NULL,
  `like_count` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`story_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `stories_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=latin1
comments | CREATE TABLE `comments` (
  `comment_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `story_id` mediumint(8) unsigned NOT NULL,
  `user_id` mediumint(8) unsigned NOT NULL,
  `date` datetime NOT NULL,
  `comment` mediumtext NOT NULL,
  `comment_title` text NOT NULL,
  PRIMARY KEY (`comment_id`),
  KEY `story_id` (`story_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`story_id`) REFERENCES `stories` (`story_id`),
  CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8

/*
 Navicat MySQL Data Transfer

 Source Server         : localhost
 Source Server Type    : MySQL
 Source Server Version : 100138
 Source Host           : localhost:3306
 Source Schema         : fifa

 Target Server Type    : MySQL
 Target Server Version : 100138
 File Encoding         : 65001

 Date: 22/03/2019 01:21:36
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for event_info
-- ----------------------------
DROP TABLE IF EXISTS `event_info`;
CREATE TABLE `event_info`  (
  `event_info_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `json_id` int(10) UNSIGNED NOT NULL,
  `type_of_event` varchar(128) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `player` varchar(128) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `time` varchar(64) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `fifa_id` int(64) UNSIGNED NOT NULL,
  `team_status` varchar(64) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  PRIMARY KEY (`event_info_id`) USING BTREE,
  INDEX `fk_event_info_fifa_id`(`fifa_id`) USING BTREE,
  CONSTRAINT `fk_event_info_fifa_id` FOREIGN KEY (`fifa_id`) REFERENCES `match_info` (`fifa_id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 47353 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for group_info
-- ----------------------------
DROP TABLE IF EXISTS `group_info`;
CREATE TABLE `group_info`  (
  `group_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `group_letter` varchar(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  PRIMARY KEY (`group_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 595 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for match_info
-- ----------------------------
DROP TABLE IF EXISTS `match_info`;
CREATE TABLE `match_info`  (
  `match_info_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `fifa_id` int(64) UNSIGNED NOT NULL,
  `venue` varchar(128) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `location` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `completion_status` varchar(64) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `time` varchar(64) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `attendance` int(255) NOT NULL,
  `stage_name` varchar(128) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `home_team_country` varchar(128) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `away_team_country` varchar(128) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `home_team_goals` int(10) NOT NULL,
  `away_team_goals` int(10) NOT NULL,
  `home_team_penalties` int(10) NOT NULL,
  `away_team_penalties` int(10) NOT NULL,
  `datetime` timestamp(0) NOT NULL DEFAULT CURRENT_TIMESTAMP(0) ON UPDATE CURRENT_TIMESTAMP(0),
  `winner` varchar(128) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `winner_code` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `last_event_update_at` timestamp(0) NOT NULL,
  PRIMARY KEY (`match_info_id`) USING BTREE,
  INDEX `fifa_id`(`fifa_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 8066 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for official
-- ----------------------------
DROP TABLE IF EXISTS `official`;
CREATE TABLE `official`  (
  `official_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `fifa_id` int(64) UNSIGNED NOT NULL,
  `referee_id` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`official_id`) USING BTREE,
  INDEX `fk_official_fifa_id`(`fifa_id`) USING BTREE,
  INDEX `fk_official_referee_id`(`referee_id`) USING BTREE,
  CONSTRAINT `fk_official_fifa_id` FOREIGN KEY (`fifa_id`) REFERENCES `match_info` (`fifa_id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_official_referee_id` FOREIGN KEY (`referee_id`) REFERENCES `referee` (`referee_id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 72130 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for player
-- ----------------------------
DROP TABLE IF EXISTS `player`;
CREATE TABLE `player`  (
  `player_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(128) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `captain` tinyint(1) UNSIGNED NOT NULL,
  `shirt_number` int(10) NOT NULL,
  `position` varchar(128) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `team_id` int(10) UNSIGNED NOT NULL,
  `starting_eleven` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`player_id`) USING BTREE,
  INDEX `player_team_id`(`team_id`) USING BTREE,
  CONSTRAINT `fk_player_team_id` FOREIGN KEY (`team_id`) REFERENCES `team` (`team_id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 51781 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for referee
-- ----------------------------
DROP TABLE IF EXISTS `referee`;
CREATE TABLE `referee`  (
  `referee_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(128) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  PRIMARY KEY (`referee_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 8588 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for statistic
-- ----------------------------
DROP TABLE IF EXISTS `statistic`;
CREATE TABLE `statistic`  (
  `statistic_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `attempts_on_goal` int(10) NOT NULL,
  `on_target` int(10) NOT NULL,
  `off_target` int(10) NOT NULL,
  `blocked` int(10) NOT NULL,
  `woodwork` int(10) NOT NULL,
  `corners` int(10) NOT NULL,
  `offsides` int(10) NOT NULL,
  `ball_possession` int(10) NOT NULL,
  `pass_accuracy` int(10) NOT NULL,
  `num_passes` int(10) NOT NULL,
  `passes_completed` int(10) NOT NULL,
  `distance_covered` int(10) NOT NULL,
  `balls_recovered` int(10) NOT NULL,
  `tackles` int(10) NOT NULL,
  `clearances` int(10) NOT NULL,
  `yellow_cards` int(10) NOT NULL,
  `red_cards` int(10) NOT NULL,
  `fouls_committed` int(10) NOT NULL,
  `tactics` varchar(128) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `fifa_id` int(64) UNSIGNED NOT NULL,
  `team_status` varchar(64) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  PRIMARY KEY (`statistic_id`) USING BTREE,
  INDEX `fk_statistic_fifa_id`(`fifa_id`) USING BTREE,
  CONSTRAINT `fk_statistic_fifa_id` FOREIGN KEY (`fifa_id`) REFERENCES `match_info` (`fifa_id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1896 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for team
-- ----------------------------
DROP TABLE IF EXISTS `team`;
CREATE TABLE `team`  (
  `team_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `country` varchar(128) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `alternate_name` varchar(128) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `fifa_code` varchar(3) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `group_id` int(10) UNSIGNED NOT NULL,
  `group_letter` varchar(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `wins` int(10) UNSIGNED NULL DEFAULT 0,
  `draws` int(10) UNSIGNED NULL DEFAULT 0,
  `losses` int(10) UNSIGNED NULL DEFAULT 0,
  `games_played` int(10) UNSIGNED NULL DEFAULT 0,
  `points` int(10) UNSIGNED NULL DEFAULT 0,
  `goals_for` int(10) NULL DEFAULT 0,
  `goals_against` int(10) NULL DEFAULT 0,
  `goal_differential` int(10) NULL DEFAULT 0,
  PRIMARY KEY (`team_id`) USING BTREE,
  INDEX `fk_team_group_id`(`group_id`) USING BTREE,
  CONSTRAINT `fk_team_group_id` FOREIGN KEY (`group_id`) REFERENCES `group_info` (`group_id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 2377 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for weather
-- ----------------------------
DROP TABLE IF EXISTS `weather`;
CREATE TABLE `weather`  (
  `weather_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `fifa_id` int(64) UNSIGNED NOT NULL,
  `humidity` int(10) NOT NULL,
  `temp_celsius` int(10) NOT NULL,
  `temp_farenheit` int(10) NOT NULL,
  `wind_speed` int(10) NOT NULL,
  `description` varchar(128) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  PRIMARY KEY (`weather_id`) USING BTREE,
  INDEX `fk_weather_fifa_id`(`fifa_id`) USING BTREE,
  CONSTRAINT `fk_weather_fifa_id` FOREIGN KEY (`fifa_id`) REFERENCES `match_info` (`fifa_id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 905 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

SET FOREIGN_KEY_CHECKS = 1;

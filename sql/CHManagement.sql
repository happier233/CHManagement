SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for doctors
-- ----------------------------
DROP TABLE IF EXISTS `doctors`;
CREATE TABLE `doctors`  (
                            `uid`         int(11)                                                      NOT NULL,
                            `name`        varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
                            `id_code`     bigint(20)                                                   NOT NULL,
                            `stu_id`      bigint(20)                                                   NOT NULL,
                            `team`        int(11)                                                      NOT NULL,
                            `position`    int(11)                                                      NOT NULL DEFAULT 0,
                            `create_time` datetime(0)                                                  NOT NULL,
                            `update_time` datetime(0)                                                  NOT NULL,
                            PRIMARY KEY (`uid`) USING BTREE,
                            INDEX `doctors_name_index`(`name`) USING BTREE,
                            INDEX `doctors_teams_id_fk` (`team`) USING BTREE,
                            CONSTRAINT `doctors_teams_id_fk` FOREIGN KEY (`team`) REFERENCES `teams` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
                            CONSTRAINT `doctors_users_id_fk` FOREIGN KEY (`uid`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for teams
-- ----------------------------
DROP TABLE IF EXISTS `teams`;
CREATE TABLE `teams`(
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nick` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB
  AUTO_INCREMENT = 8
  CHARACTER SET = utf8mb4
  COLLATE = utf8mb4_general_ci
  ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users`(
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nick` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `permission` int(11) NOT NULL DEFAULT 0,
  `create_time` datetime(0) NOT NULL,
  `update_time` datetime(0) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `users_nick_uindex`(`nick`) USING BTREE,
  UNIQUE INDEX `users_email_uindex`(`email`) USING BTREE
) ENGINE = InnoDB
  AUTO_INCREMENT = 11
  CHARACTER SET = utf8mb4
  COLLATE = utf8mb4_general_ci
  ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for work_detail
-- ----------------------------
DROP TABLE IF EXISTS `work_detail`;
CREATE TABLE `work_detail`  (
                                `wid`          int(11)                                                       NOT NULL,
                                `name`         varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci  NOT NULL,
                                `tel`          bigint(20)                                                    NOT NULL,
                                `stu_id`       bigint(20)                                                    NOT NULL,
                                `college`      varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci  NOT NULL,
                                `evaluation`   int(11)                                                       NOT NULL DEFAULT 0 COMMENT '评分从0-5',
                                `message`      varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
                                `confirm_time` datetime(0)                                                   NOT NULL,
                                PRIMARY KEY (`wid`) USING BTREE,
                                CONSTRAINT `work_detail_works_id_fk` FOREIGN KEY (`wid`) REFERENCES `works` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for works
-- ----------------------------
DROP TABLE IF EXISTS `works`;
CREATE TABLE `works`  (
                          `id` int(11) NOT NULL AUTO_INCREMENT,
                          `doctor` int(11) NOT NULL,
                          `start_time` datetime(0) NOT NULL,
                          `duration` int(11) NOT NULL DEFAULT 0 COMMENT '单位是30分钟',
                          `product` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
                          `problem` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
                          `solution` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
                          `create_time` datetime(0) NOT NULL,
                          `update_time` datetime(0) NOT NULL,
                          PRIMARY KEY (`id`) USING BTREE,
                          INDEX `works_doctors_id_fk`(`doctor`) USING BTREE,
                          CONSTRAINT `works_doctors_id_fk` FOREIGN KEY (`doctor`) REFERENCES `doctors` (`uid`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE = InnoDB
  AUTO_INCREMENT = 9
  CHARACTER SET = utf8mb4
  COLLATE = utf8mb4_general_ci
  ROW_FORMAT = Dynamic;

-- ----------------------------
-- View structure for doctors_wt
-- ----------------------------
DROP VIEW IF EXISTS `doctors_wt`;
CREATE ALGORITHM = UNDEFINED SQL SECURITY DEFINER VIEW `doctors_wt` AS
select `doctors`.`uid`         AS `uid`,
       `doctors`.`name`        AS `name`,
       `doctors`.`id_code`     AS `id_code`,
       `doctors`.`stu_id`      AS `stu_id`,
       `doctors`.`team`        AS `team`,
       `doctors`.`position`    AS `position`,
       `doctors`.`create_time` AS `create_time`,
       `doctors`.`update_time` AS `update_time`,
       `teams`.`nick`          AS `tnick`
from (`doctors`
         join `teams`)
where (`doctors`.`team` = `teams`.`id`);

SET FOREIGN_KEY_CHECKS = 1;

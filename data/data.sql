SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema profzone_accountdb
-- -----------------------------------------------------
DROP SCHEMA IF EXISTS `profzone_accountdb` ;
CREATE SCHEMA IF NOT EXISTS `profzone_accountdb` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
-- -----------------------------------------------------
-- Schema profzone_godwar_gamedb
-- -----------------------------------------------------
DROP SCHEMA IF EXISTS `profzone_godwar_gamedb` ;
CREATE SCHEMA IF NOT EXISTS `profzone_godwar_gamedb` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `profzone_accountdb` ;

-- -----------------------------------------------------
-- Table `profzone_accountdb`.`accounts`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `profzone_accountdb`.`accounts` ;

CREATE TABLE IF NOT EXISTS `profzone_accountdb`.`accounts` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `name` CHAR(32) NOT NULL,
  `pass` CHAR(64) NOT NULL,
  `email` CHAR(64) NOT NULL,
  `regtime` INT NOT NULL,
  `lasttime` INT NOT NULL,
  `sina_weibo_id` CHAR(32) NOT NULL DEFAULT '',
  `sina_weibo_token` CHAR(64) NOT NULL DEFAULT '',
  `tencent_weibo_id` CHAR(32) NOT NULL DEFAULT '',
  `tencent_weibo_key` CHAR(64) NOT NULL DEFAULT '',
  `tencent_weibo_token` CHAR(64) NOT NULL DEFAULT '',
  `renren_id` CHAR(32) NOT NULL DEFAULT '',
  `renren_name` CHAR(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`))
AUTO_INCREMENT = 1004000001;

USE `profzone_godwar_gamedb` ;

-- -----------------------------------------------------
-- Table `profzone_godwar_gamedb`.`roles`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `profzone_godwar_gamedb`.`roles` ;

CREATE TABLE IF NOT EXISTS `profzone_godwar_gamedb`.`roles` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `account_id` BIGINT NOT NULL,
  `name` CHAR(32) NOT NULL,
  `level` INT NOT NULL DEFAULT 1,
  `vitality` INT NOT NULL DEFAULT 100,
  `gold` BIGINT NOT NULL DEFAULT 0,
  `gold_inc` FLOAT NOT NULL DEFAULT 0,
  `exp` BIGINT NOT NULL DEFAULT 0,
  `exp_inc` FLOAT NOT NULL DEFAULT 0,
  `nextexp` BIGINT NOT NULL DEFAULT 0,
  `race` ENUM('01001','01002','01003','01004','01005','01006') NOT NULL DEFAULT '01001' COMMENT '01001=�' /* comment truncated */ /*类
01002=天使
01003=恶魔
01004=精灵
01005=亡灵
01006=泰坦*/,
  `job` INT NOT NULL DEFAULT 0 COMMENT '0 = 初' /* comment truncated */ /*��者
一转
1 = 战士
2 = 法师
3 = 使者
二转
4 = 佣兵
5 = 抵抗者
6 = 咒术师
7 = 贤者
8 = 牧师
9 = 守护者*/,
  `health_base` INT NOT NULL DEFAULT 0,
  `health_max` INT NOT NULL DEFAULT 0,
  `health` INT NOT NULL DEFAULT 0,
  `atk_base` INT NOT NULL DEFAULT 0,
  `atk` INT NOT NULL DEFAULT 0 COMMENT '攻击力',
  `def_base` INT NOT NULL DEFAULT 0,
  `def` INT NOT NULL DEFAULT 0 COMMENT '防御力',
  `mdef_base` INT NOT NULL DEFAULT 0,
  `mdef` INT NOT NULL DEFAULT 0 COMMENT '魔抗力',
  `hit_base` INT NOT NULL DEFAULT 0,
  `hit` INT NOT NULL DEFAULT 0 COMMENT '命中',
  `crit_base` INT NOT NULL DEFAULT 0,
  `crit` INT NOT NULL DEFAULT 0,
  `flee_base` INT NOT NULL DEFAULT 0,
  `flee` INT NOT NULL DEFAULT 0 COMMENT '闪避',
  `gift` TEXT NOT NULL,
  `skill_trigger_base` DOUBLE(5,4) NOT NULL DEFAULT 0,
  `skill_trigger` DOUBLE(5,4) NOT NULL DEFAULT 0,
  `skill` TEXT NOT NULL,
  `main_skill` CHAR(8) NOT NULL DEFAULT '',
  `passive_skill` TEXT NOT NULL,
  `createtime` INT NOT NULL DEFAULT 0 COMMENT '创建时间',
  `lasttime` INT NOT NULL DEFAULT 0 COMMENT '上次登录时间',
  `map_id` INT NOT NULL DEFAULT 0,
  `gift_point` INT NOT NULL DEFAULT 0,
  `battletime` INT NOT NULL DEFAULT 0,
  `next_battletime` INT NOT NULL DEFAULT 0,
  `gathertime` INT NOT NULL DEFAULT 0,
  `next_gathertime` INT NOT NULL DEFAULT 0,
  `current_action` TINYINT NOT NULL DEFAULT 0 COMMENT '1=�' /* comment truncated */ /*斗
2=采集*/,
  `append_status` TEXT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `account_id` (`account_id` ASC),
  INDEX `role_name` (`name` ASC))
ENGINE = InnoDB
AUTO_INCREMENT = 1005100001
PACK_KEYS = 1;


-- -----------------------------------------------------
-- Table `profzone_godwar_gamedb`.`equipments`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `profzone_godwar_gamedb`.`equipments` ;

CREATE TABLE IF NOT EXISTS `profzone_godwar_gamedb`.`equipments` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `role_id` BIGINT NOT NULL,
  `original_id` INT NOT NULL DEFAULT 0,
  `name` CHAR(40) NOT NULL DEFAULT '',
  `position` TINYINT NOT NULL DEFAULT 0 COMMENT '1=�' /* comment truncated */ /*器
2=头盔
3=护手
4=盔甲
5=腰带
6=鞋子
7=戒指
8=项链*/,
  `level` INT NOT NULL DEFAULT 0,
  `grade` TINYINT NOT NULL DEFAULT 0 COMMENT '0=�' /* comment truncated */ /*通
1=蓝装
2=绿装
3=紫装
4=金装*/,
  `upgrade_level` INT NOT NULL DEFAULT 0,
  `upgrade_level_max` INT NOT NULL DEFAULT 0,
  `job` CHAR(20) NOT NULL DEFAULT '[]',
  `atk_base` INT NOT NULL DEFAULT 0,
  `def_base` INT NOT NULL DEFAULT 0,
  `mdef_base` INT NOT NULL DEFAULT 0,
  `health_max_base` INT NOT NULL DEFAULT 0,
  `hit_base` INT NOT NULL DEFAULT 0,
  `flee_base` INT NOT NULL DEFAULT 0,
  `atk_inc` INT NOT NULL DEFAULT 0,
  `def_inc` INT NOT NULL DEFAULT 0,
  `mdef_inc` INT NOT NULL DEFAULT 0,
  `health_max_inc` INT NOT NULL DEFAULT 0,
  `hit_inc` INT NOT NULL DEFAULT 0,
  `flee_inc` INT NOT NULL DEFAULT 0,
  `atk_upgrade` INT NOT NULL DEFAULT 0,
  `def_upgrade` INT NOT NULL DEFAULT 0,
  `mdef_upgrade` INT NOT NULL DEFAULT 0,
  `health_max_upgrade` INT NOT NULL DEFAULT 0,
  `hit_upgrade` INT NOT NULL DEFAULT 0,
  `flee_upgrade` INT NOT NULL DEFAULT 0,
  `magic_words` TEXT NOT NULL,
  `price` INT NOT NULL DEFAULT 0,
  `is_equipped` TINYINT NOT NULL DEFAULT 0,
  `is_locked` TINYINT NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  INDEX `role_id` (`role_id` ASC),
  INDEX `is_equipped` (`role_id` ASC, `is_equipped` ASC))
ENGINE = InnoDB
AUTO_INCREMENT = 100000000001;


-- -----------------------------------------------------
-- Table `profzone_godwar_gamedb`.`market`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `profzone_godwar_gamedb`.`market` ;

CREATE TABLE IF NOT EXISTS `profzone_godwar_gamedb`.`market` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `role_id` BIGINT NOT NULL DEFAULT 0,
  `price` BIGINT NOT NULL DEFAULT 0,
  `property` TEXT NOT NULL,
  `starttime` INT NOT NULL DEFAULT 0,
  `endtime` INT NOT NULL DEFAULT 0,
  `status` TINYINT NOT NULL DEFAULT 0 COMMENT '1=正�' /* comment truncated */ /*�出售
2=交易成功
3=取消*/,
  `type` TINYINT NOT NULL COMMENT '1=�' /* comment truncated */ /*器
2=道具*/,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `profzone_godwar_gamedb`.`thirdparts`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `profzone_godwar_gamedb`.`thirdparts` ;

CREATE TABLE IF NOT EXISTS `profzone_godwar_gamedb`.`thirdparts` (
  `role_id` BIGINT NOT NULL,
  `sina_weibo_id` CHAR(32) NOT NULL DEFAULT '',
  `sina_weibo_token` CHAR(64) NOT NULL DEFAULT '',
  `sina_weibo_nickname` CHAR(32) NOT NULL DEFAULT '',
  `tencent_weibo_nickname` CHAR(32) NOT NULL DEFAULT '',
  `tencent_weibo_code` CHAR(32) NOT NULL DEFAULT '',
  `tencent_weibo_expire_in` INT NOT NULL DEFAULT 0,
  `tencent_weibo_refresh_token` CHAR(64) NOT NULL DEFAULT '',
  `tencent_weibo_token` CHAR(64) NOT NULL DEFAULT '',
  `tencent_weibo_key` CHAR(64) NOT NULL DEFAULT '',
  `tencent_weibo_id` CHAR(32) NOT NULL DEFAULT '',
  `renren_id` CHAR(32) NOT NULL DEFAULT '',
  `renren_name` CHAR(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`role_id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `profzone_godwar_gamedb`.`items`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `profzone_godwar_gamedb`.`items` ;

CREATE TABLE IF NOT EXISTS `profzone_godwar_gamedb`.`items` (
  `id` BIGINT NOT NULL DEFAULT 0,
  `role_id` BIGINT NOT NULL DEFAULT 0,
  `name` CHAR(32) NOT NULL DEFAULT '',
  `type` TINYINT NOT NULL DEFAULT 0,
  `count` INT NOT NULL DEFAULT 0,
  `price` INT NOT NULL DEFAULT 0,
  `is_locked` TINYINT NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`, `role_id`))
ENGINE = InnoDB
AUTO_INCREMENT = 200000000001;


-- -----------------------------------------------------
-- Table `profzone_godwar_gamedb`.`alchemy`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `profzone_godwar_gamedb`.`alchemy` ;

CREATE TABLE IF NOT EXISTS `profzone_godwar_gamedb`.`alchemy` (
  `role_id` BIGINT NOT NULL DEFAULT 0,
  `id` BIGINT NOT NULL DEFAULT 0,
  `name` CHAR(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`role_id`, `id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `profzone_godwar_gamedb`.`alchemy_queue`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `profzone_godwar_gamedb`.`alchemy_queue` ;

CREATE TABLE IF NOT EXISTS `profzone_godwar_gamedb`.`alchemy_queue` (
  `role_id` BIGINT NOT NULL DEFAULT 0,
  `id` BIGINT NOT NULL DEFAULT 0,
  `product_id` BIGINT NOT NULL DEFAULT 0,
  `name` CHAR(32) NOT NULL DEFAULT '',
  `starttime` INT NOT NULL DEFAULT 0,
  `endtime` INT NOT NULL DEFAULT 0,
  `status` INT NOT NULL DEFAULT 0 COMMENT '0=运' /* comment truncated */ /*��中
1=已完成
2=已接收*/,
  PRIMARY KEY (`role_id`, `id`))
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

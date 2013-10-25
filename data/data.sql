SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

DROP SCHEMA IF EXISTS `godwar_accountdb` ;
CREATE SCHEMA IF NOT EXISTS `godwar_accountdb` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
DROP SCHEMA IF EXISTS `godwar_gamedb` ;
CREATE SCHEMA IF NOT EXISTS `godwar_gamedb` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `godwar_accountdb` ;

-- -----------------------------------------------------
-- Table `godwar_accountdb`.`accounts`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `godwar_accountdb`.`accounts` ;

CREATE TABLE IF NOT EXISTS `godwar_accountdb`.`accounts` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `name` CHAR(32) NOT NULL,
  `pass` CHAR(64) NOT NULL,
  `email` CHAR(64) NOT NULL,
  `regtime` INT NOT NULL,
  `lasttime` INT NOT NULL,
  PRIMARY KEY (`id`))
AUTO_INCREMENT = 1004000001;

USE `godwar_gamedb` ;

-- -----------------------------------------------------
-- Table `godwar_gamedb`.`roles`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `godwar_gamedb`.`roles` ;

CREATE TABLE IF NOT EXISTS `godwar_gamedb`.`roles` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `account_id` BIGINT NOT NULL,
  `role_name` CHAR(32) NOT NULL,
  `role_level` INT NOT NULL,
  `role_race` INT NOT NULL COMMENT '1=人类\n2=天使\n3=恶魔\n4=精灵\n5=亡灵\n6=泰坦',
  `role_job` INT NOT NULL COMMENT '0 = 初心者\n一转\n1 = 战士\n2 = 法师\n3 = 使者\n二转\n4 = 佣兵\n5 = 抵抗者\n6 = 咒术师\n7 = 贤者\n8 = 牧师\n9 = 守护者',
  `role_health_max` INT NOT NULL,
  `role_health` INT NOT NULL,
  `role_atk` INT NOT NULL COMMENT '攻击力',
  `role_def` INT NOT NULL COMMENT '防御力',
  `role_mdef` INT NOT NULL COMMENT '魔抗力',
  `role_hit` INT NOT NULL COMMENT '命中',
  `role_flee` INT NOT NULL COMMENT '闪避',
  `role_skill_config` TEXT NOT NULL,
  `role_createtime` INT NOT NULL COMMENT '创建时间',
  `role_lasttime` INT NOT NULL COMMENT '上次登录时间',
  PRIMARY KEY (`id`))
AUTO_INCREMENT = 1005100001;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

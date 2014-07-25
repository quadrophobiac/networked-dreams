SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

DROP SCHEMA IF EXISTS `dreamdemo` ;
CREATE SCHEMA IF NOT EXISTS `dreamdemo` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `dreamdemo` ;

-- -----------------------------------------------------
-- Table `dreamdemo`.`timeuse`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `dreamdemo`.`timeuse` ;

CREATE TABLE IF NOT EXISTS `dreamdemo`.`timeuse` (
  `timeuse_id` INT(1) NOT NULL,
  `category` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`timeuse_id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `dreamdemo`.`cause_dict`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `dreamdemo`.`cause_dict` ;

CREATE TABLE IF NOT EXISTS `dreamdemo`.`cause_dict` (
  `cause_id` INT(4) NOT NULL,
  `name` VARCHAR(200) NOT NULL,
  `timeuse_id` INT(1) NOT NULL,
  PRIMARY KEY (`cause_id`),
  INDEX `fk_cause_dict_timeuse1_idx` (`timeuse_id` ASC),
  CONSTRAINT `fk_cause_dict_timeuse1`
    FOREIGN KEY (`timeuse_id`)
    REFERENCES `dreamdemo`.`timeuse` (`timeuse_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `dreamdemo`.`mmbr`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `dreamdemo`.`mmbr` ;

CREATE TABLE IF NOT EXISTS `dreamdemo`.`mmbr` (
  `usrid` INT(10) NOT NULL,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `ualias` VARCHAR(100) NOT NULL,
  `password` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`usrid`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `dreamdemo`.`dr_category`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `dreamdemo`.`dr_category` ;

CREATE TABLE IF NOT EXISTS `dreamdemo`.`dr_category` (
  `id` INT(3) NOT NULL,
  `name` VARCHAR(245) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `dreamdemo`.`dream`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `dreamdemo`.`dream` ;

CREATE TABLE IF NOT EXISTS `dreamdemo`.`dream` (
  `d_id` INT(15) NOT NULL,
  `usrid` INT(10) NOT NULL,
  `d_date` DATE NOT NULL,
  `category` INT(3) NOT NULL,
  `comments` TEXT NULL DEFAULT NULL,
  `numppl` INT(3) NULL DEFAULT NULL,
  `numobj` INT(3) NULL DEFAULT NULL,
  `numplace` INT(3) NULL DEFAULT NULL,
  `numeact` INT(3) NULL DEFAULT NULL,
  PRIMARY KEY (`d_id`),
  INDEX `fk_dreamdemo_mmbr_idx` (`usrid` ASC),
  INDEX `fk_dreamdemo_dr_category1_idx` (`category` ASC),
  CONSTRAINT `fk_dreamdemo_mmbr`
    FOREIGN KEY (`usrid`)
    REFERENCES `dreamdemo`.`mmbr` (`usrid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_dreamdemo_dr_category1`
    FOREIGN KEY (`category`)
    REFERENCES `dreamdemo`.`dr_category` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `dreamdemo`.`cause`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `dreamdemo`.`cause` ;

CREATE TABLE IF NOT EXISTS `dreamdemo`.`cause` (
  `id` INT(10) NOT NULL,
  `d_id` INT(15) NOT NULL,
  `cause_cat` INT(4) NOT NULL,
  `comments` TEXT NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_cause_data_dreamdemo1_idx` (`d_id` ASC),
  INDEX `fk_cause_data_causes1_idx` (`cause_cat` ASC),
  CONSTRAINT `fk_cause_data_dreamdemo1`
    FOREIGN KEY (`d_id`)
    REFERENCES `dreamdemo`.`dream` (`d_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_cause_data_causes1`
    FOREIGN KEY (`cause_cat`)
    REFERENCES `dreamdemo`.`cause_dict` (`cause_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `dreamdemo`.`dr_activity`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `dreamdemo`.`dr_activity` ;

CREATE TABLE IF NOT EXISTS `dreamdemo`.`dr_activity` (
  `id` INT(10) NOT NULL,
  `d_id` INT(15) NOT NULL,
  `em_activity` VARCHAR(100) NULL DEFAULT NULL,
  PRIMARY KEY (`id`, `d_id`),
  INDEX `fk_dr_activity_dreamdemo1_idx` (`d_id` ASC),
  CONSTRAINT `fk_dr_activity_dreamdemo1`
    FOREIGN KEY (`d_id`)
    REFERENCES `dreamdemo`.`dream` (`d_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `dreamdemo`.`dr_locale`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `dreamdemo`.`dr_locale` ;

CREATE TABLE IF NOT EXISTS `dreamdemo`.`dr_locale` (
  `id` INT(10) NOT NULL,
  `d_id` INT(15) NOT NULL,
  `location` VARCHAR(100) NULL DEFAULT NULL,
  PRIMARY KEY (`id`, `d_id`),
  INDEX `fk_dr_locale_dreamdemo1_idx` (`d_id` ASC),
  CONSTRAINT `fk_dr_locale_dreamdemo1`
    FOREIGN KEY (`d_id`)
    REFERENCES `dreamdemo`.`dream` (`d_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `dreamdemo`.`dr_object`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `dreamdemo`.`dr_object` ;

CREATE TABLE IF NOT EXISTS `dreamdemo`.`dr_object` (
  `id` INT(10) NOT NULL,
  `d_id` INT(15) NOT NULL,
  `object` VARCHAR(100) NULL DEFAULT NULL,
  PRIMARY KEY (`id`, `d_id`),
  INDEX `fk_dr_object_dreamdemo1_idx` (`d_id` ASC),
  CONSTRAINT `fk_dr_object_dreamdemo1`
    FOREIGN KEY (`d_id`)
    REFERENCES `dreamdemo`.`dream` (`d_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `dreamdemo`.`dr_people`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `dreamdemo`.`dr_people` ;

CREATE TABLE IF NOT EXISTS `dreamdemo`.`dr_people` (
  `id` INT(10) NOT NULL,
  `d_id` INT(15) NOT NULL,
  `person` VARCHAR(100) NULL DEFAULT NULL,
  PRIMARY KEY (`id`, `d_id`),
  INDEX `fk_dr_people_dreamdemo1_idx` (`d_id` ASC),
  CONSTRAINT `fk_dr_people_dreamdemo1`
    FOREIGN KEY (`d_id`)
    REFERENCES `dreamdemo`.`dream` (`d_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `dreamdemo`.`mcountry`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `dreamdemo`.`mcountry` ;

CREATE TABLE IF NOT EXISTS `dreamdemo`.`mcountry` (
  `state_id` INT(4) NOT NULL,
  `name` VARCHAR(200) NOT NULL,
  PRIMARY KEY (`state_id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `dreamdemo`.`mcity`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `dreamdemo`.`mcity` ;

CREATE TABLE IF NOT EXISTS `dreamdemo`.`mcity` (
  `city_id` INT(4) NOT NULL,
  `state_id` INT(4) NOT NULL,
  `name` VARCHAR(200) NOT NULL,
  PRIMARY KEY (`city_id`),
  INDEX `fk_mcity_mcountry1_idx` (`state_id` ASC),
  CONSTRAINT `fk_mcity_mcountry1`
    FOREIGN KEY (`state_id`)
    REFERENCES `dreamdemo`.`mcountry` (`state_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `dreamdemo`.`mjob`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `dreamdemo`.`mjob` ;

CREATE TABLE IF NOT EXISTS `dreamdemo`.`mjob` (
  `job_id` INT(4) NOT NULL,
  `name` VARCHAR(200) NOT NULL,
  PRIMARY KEY (`job_id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `dreamdemo`.`mmarital`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `dreamdemo`.`mmarital` ;

CREATE TABLE IF NOT EXISTS `dreamdemo`.`mmarital` (
  `m_id` INT(1) NOT NULL,
  `name` VARCHAR(200) NOT NULL,
  PRIMARY KEY (`m_id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `dreamdemo`.`mmbr_profile`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `dreamdemo`.`mmbr_profile` ;

CREATE TABLE IF NOT EXISTS `dreamdemo`.`mmbr_profile` (
  `usrid` INT(10) NOT NULL,
  `dob` DATE NOT NULL,
  `gender` VARCHAR(1) NOT NULL,
  `marital` INT(1) NOT NULL,
  `job` INT(4) NOT NULL,
  `learning` VARCHAR(1) NOT NULL,
  `country` INT(4) NOT NULL,
  `city` INT(4) NOT NULL,
  `passion` VARCHAR(100) NULL,
  PRIMARY KEY (`usrid`),
  INDEX `fk_mmbr_profile_mmbr1_idx` (`usrid` ASC),
  INDEX `fk_mmbr_profile_mjob1_idx` (`job` ASC),
  INDEX `fk_mmbr_profile_mmarital1_idx` (`marital` ASC),
  INDEX `fk_mmbr_profile_mcountry1_idx` (`country` ASC),
  INDEX `fk_mmbr_profile_mcity1_idx` (`city` ASC),
  CONSTRAINT `fk_mmbr_profile_mmbr1`
    FOREIGN KEY (`usrid`)
    REFERENCES `dreamdemo`.`mmbr` (`usrid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_mmbr_profile_mjob1`
    FOREIGN KEY (`job`)
    REFERENCES `dreamdemo`.`mjob` (`job_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_mmbr_profile_mmarital1`
    FOREIGN KEY (`marital`)
    REFERENCES `dreamdemo`.`mmarital` (`m_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_mmbr_profile_mcountry1`
    FOREIGN KEY (`country`)
    REFERENCES `dreamdemo`.`mcountry` (`state_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_mmbr_profile_mcity1`
    FOREIGN KEY (`city`)
    REFERENCES `dreamdemo`.`mcity` (`city_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `dreamdemo`.`user_suggestion_dr`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `dreamdemo`.`user_suggestion_dr` ;

CREATE TABLE IF NOT EXISTS `dreamdemo`.`user_suggestion_dr` (
  `id` INT NOT NULL,
  `suggestion` VARCHAR(200) NULL,
  `d_id` INT(15) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_user_suggestion_dr_dreamdemo1_idx` (`d_id` ASC),
  CONSTRAINT `fk_user_suggestion_dr_dreamdemo1`
    FOREIGN KEY (`d_id`)
    REFERENCES `dreamdemo`.`dream` (`d_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `dreamdemo`.`user_suggestion_cause`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `dreamdemo`.`user_suggestion_cause` ;

CREATE TABLE IF NOT EXISTS `dreamdemo`.`user_suggestion_cause` (
  `id` INT NOT NULL,
  `suggestion` VARCHAR(200) NULL,
  `d_id` INT(15) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_user_suggestion_cause_dreamdemo1_idx` (`d_id` ASC),
  CONSTRAINT `fk_user_suggestion_cause_dreamdemo1`
    FOREIGN KEY (`d_id`)
    REFERENCES `dreamdemo`.`dream` (`d_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

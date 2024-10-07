-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema streamingPortal
-- -----------------------------------------------------
--
--
--

-- -----------------------------------------------------
-- Schema streamingPortal
--
--
--
--
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `streamingPortal` DEFAULT CHARACTER SET utf8 COLLATE utf8_bin ;
-- -----------------------------------------------------
-- Schema streamingportal
-- -----------------------------------------------------
USE `streamingPortal` ;

-- -----------------------------------------------------
-- Table `streamingPortal`.`offers`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `streamingPortal`.`offers` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(255) NOT NULL,
  `trailer` VARCHAR(255) NOT NULL,
  `fsk` TINYINT NOT NULL,
  `posterLink` VARCHAR(255) NOT NULL,
  `originalTitle` VARCHAR(255) NOT NULL,
  `rating` DECIMAL(3,2) NOT NULL,
  `description` TEXT NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `streamingPortal`.`providers`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `streamingPortal`.`providers` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `affiliateLink` VARCHAR(255) NOT NULL,
  `logo` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `streamingPortal`.`abos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `streamingPortal`.`abos` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `cost` DECIMAL(11,2) NOT NULL,
  `provider_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_abos_provider_idx` (`provider_id` ASC),
  CONSTRAINT `fk_abos_provider`
    FOREIGN KEY (`provider_id`)
    REFERENCES `streamingPortal`.`providers` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `streamingPortal`.`offersHasProviders`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `streamingPortal`.`offersHasProviders` (
  `provider_id` INT NOT NULL,
  `offers_id` INT NOT NULL,
  PRIMARY KEY (`provider_id`, `offers_id`),
  INDEX `fk_provider_has_movie_movie1_idx` (`offers_id` ASC),
  INDEX `fk_provider_has_movie_provider1_idx` (`provider_id` ASC),
  CONSTRAINT `fk_provider_has_movie_provider1`
    FOREIGN KEY (`provider_id`)
    REFERENCES `streamingPortal`.`providers` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_provider_has_movie_movie1`
    FOREIGN KEY (`offers_id`)
    REFERENCES `streamingPortal`.`offers` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `streamingPortal`.`filmIndustryProfessional`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `streamingPortal`.`filmIndustryProfessional` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `firstname` VARCHAR(255) NOT NULL,
  `lastname` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `streamingPortal`.`seasons`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `streamingPortal`.`seasons` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `number` INT NOT NULL,
  `offers_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_seasons_offers1_idx` (`offers_id` ASC),
  CONSTRAINT `fk_seasons_offers1`
    FOREIGN KEY (`offers_id`)
    REFERENCES `streamingPortal`.`offers` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `streamingPortal`.`episodes`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `streamingPortal`.`episodes` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `number` INT NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `duration` INT NOT NULL,
  `releaseYear` INT NOT NULL,
  `seasons_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_episodes_seasons1_idx` (`seasons_id` ASC),
  CONSTRAINT `fk_episodes_seasons1`
    FOREIGN KEY (`seasons_id`)
    REFERENCES `streamingPortal`.`seasons` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `streamingPortal`.`directors`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `streamingPortal`.`directors` (
  `movies_id` INT NOT NULL,
  `filmIndustryProfessional_id` INT NOT NULL,
  PRIMARY KEY (`movies_id`, `filmIndustryProfessional_id`),
  INDEX `fk_famouspersons_has_movies_movies1_idx` (`movies_id` ASC),
  INDEX `fk_directors_filmIndustryProfessional1_idx` (`filmIndustryProfessional_id` ASC),
  CONSTRAINT `fk_famouspersons_has_movies_movies1`
    FOREIGN KEY (`movies_id`)
    REFERENCES `streamingPortal`.`offers` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_directors_filmIndustryProfessional1`
    FOREIGN KEY (`filmIndustryProfessional_id`)
    REFERENCES `streamingPortal`.`filmIndustryProfessional` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `streamingPortal`.`actors`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `streamingPortal`.`actors` (
  `movies_id` INT NOT NULL,
  `filmIndustryProfessional_id` INT NOT NULL,
  PRIMARY KEY (`movies_id`, `filmIndustryProfessional_id`),
  INDEX `fk_famouspersons_has_movies1_movies1_idx` (`movies_id` ASC),
  INDEX `fk_actors_filmIndustryProfessional1_idx` (`filmIndustryProfessional_id` ASC),
  CONSTRAINT `fk_famouspersons_has_movies1_movies1`
    FOREIGN KEY (`movies_id`)
    REFERENCES `streamingPortal`.`offers` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_actors_filmIndustryProfessional1`
    FOREIGN KEY (`filmIndustryProfessional_id`)
    REFERENCES `streamingPortal`.`filmIndustryProfessional` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `streamingPortal`.`movie`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `streamingPortal`.`movie` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `releaseYear` INT NOT NULL,
  `duration` INT NOT NULL,
  `offers_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_movie_offers1_idx` (`offers_id` ASC),
  CONSTRAINT `fk_movie_offers1`
    FOREIGN KEY (`offers_id`)
    REFERENCES `streamingPortal`.`offers` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `streamingPortal`.`languages`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `streamingPortal`.`languages` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `language` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `streamingPortal`.`hasSubs`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `streamingPortal`.`hasSubs` (
  `offers_id` INT NOT NULL,
  `languages_id` INT NOT NULL,
  PRIMARY KEY (`offers_id`, `languages_id`),
  INDEX `fk_offers_has_languages_languages1_idx` (`languages_id` ASC),
  INDEX `fk_offers_has_languages_offers1_idx` (`offers_id` ASC),
  CONSTRAINT `fk_offers_has_languages_offers1`
    FOREIGN KEY (`offers_id`)
    REFERENCES `streamingPortal`.`offers` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_offers_has_languages_languages1`
    FOREIGN KEY (`languages_id`)
    REFERENCES `streamingPortal`.`languages` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `streamingPortal`.`users`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `streamingPortal`.`users` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `firstname` VARCHAR(255) NOT NULL,
  `lastname` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `role` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `streamingPortal`.`watchlists`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `streamingPortal`.`watchlists` (
  `user_id` INT NOT NULL,
  `offers_id` INT NOT NULL,
  PRIMARY KEY (`user_id`, `offers_id`),
  INDEX `fk_user_has_offers_offers1_idx` (`offers_id` ASC),
  INDEX `fk_user_has_offers_user1_idx` (`user_id` ASC),
  CONSTRAINT `fk_user_has_offers_user1`
    FOREIGN KEY (`user_id`)
    REFERENCES `streamingPortal`.`users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_user_has_offers_offers1`
    FOREIGN KEY (`offers_id`)
    REFERENCES `streamingPortal`.`offers` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `streamingPortal`.`hasDubs`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `streamingPortal`.`hasDubs` (
  `languages_id` INT NOT NULL,
  `offers_id` INT NOT NULL,
  PRIMARY KEY (`languages_id`, `offers_id`),
  INDEX `fk_languages_has_offers_offers1_idx` (`offers_id` ASC),
  INDEX `fk_languages_has_offers_languages1_idx` (`languages_id` ASC),
  CONSTRAINT `fk_languages_has_offers_languages1`
    FOREIGN KEY (`languages_id`)
    REFERENCES `streamingPortal`.`languages` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_languages_has_offers_offers1`
    FOREIGN KEY (`offers_id`)
    REFERENCES `streamingPortal`.`offers` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `streamingPortal`.`genres`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `streamingPortal`.`genres` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `streamingPortal`.`offersHasGenres`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `streamingPortal`.`offersHasGenres` (
  `offers_id` INT NOT NULL,
  `genres_id` INT NOT NULL,
  PRIMARY KEY (`offers_id`, `genres_id`),
  INDEX `fk_offers_has_genres_genres1_idx` (`genres_id` ASC),
  INDEX `fk_offers_has_genres_offers1_idx` (`offers_id` ASC),
  CONSTRAINT `fk_offers_has_genres_offers1`
    FOREIGN KEY (`offers_id`)
    REFERENCES `streamingPortal`.`offers` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_offers_has_genres_genres1`
    FOREIGN KEY (`genres_id`)
    REFERENCES `streamingPortal`.`genres` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Mar 26, 2023 at 11:57 AM
-- Server version: 10.5.17-MariaDB-cll-lve
-- PHP Version: 8.2.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `flisoldf_api`
--
CREATE DATABASE IF NOT EXISTS `flisoldf_api` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `flisoldf_api`;

-- --------------------------------------------------------

--
-- Table structure for table `collaboration_area`
--

DROP TABLE IF EXISTS `collaboration_area`;
CREATE TABLE IF NOT EXISTS `collaboration_area` (
                                                    `id` int(11) NOT NULL AUTO_INCREMENT,
                                                    `name` varchar(100) NOT NULL,
                                                    PRIMARY KEY (`id`)
);

-- --------------------------------------------------------

--
-- Table structure for table `collaborator`
--

DROP TABLE IF EXISTS `collaborator`;
CREATE TABLE IF NOT EXISTS `collaborator` (
                                              `id` int(11) NOT NULL AUTO_INCREMENT,
                                              `edition_id` int(11) NOT NULL,
                                              `person_id` int(11) NOT NULL,
                                              `audited_at` datetime DEFAULT NULL,
                                              `audit_note` text DEFAULT NULL,
                                              `approved` smallint(1) DEFAULT NULL,
                                              `confirmed_at` datetime DEFAULT NULL,
                                              `created_at` datetime DEFAULT NULL,
                                              `updated_at` datetime DEFAULT NULL,
                                              `removed_at` datetime DEFAULT NULL,
                                              PRIMARY KEY (`id`),
                                              KEY `fk_collaborator_edition_idx` (`edition_id`),
                                              KEY `fk_collaborator_person_idx` (`person_id`)
);

-- --------------------------------------------------------

--
-- Table structure for table `collaborator_area`
--

DROP TABLE IF EXISTS `collaborator_area`;
CREATE TABLE IF NOT EXISTS `collaborator_area` (
                                                   `id` int(11) NOT NULL AUTO_INCREMENT,
                                                   `collaborator_id` int(11) NOT NULL,
                                                   `collaboration_area_id` int(11) NOT NULL,
                                                   `created_at` datetime DEFAULT NULL,
                                                   `updated_at` datetime DEFAULT NULL,
                                                   `removed_at` datetime DEFAULT NULL,
                                                   PRIMARY KEY (`id`),
                                                   KEY `fk_collaborator_area_collaborator_idx` (`collaborator_id`),
                                                   KEY `fk_collaborator_area_collaboration_area_idx` (`collaboration_area_id`)
);

-- --------------------------------------------------------

--
-- Table structure for table `collaborator_availability`
--

DROP TABLE IF EXISTS `collaborator_availability`;
CREATE TABLE IF NOT EXISTS `collaborator_availability` (
                                                           `id` int(11) NOT NULL AUTO_INCREMENT,
                                                           `name` varchar(60) NOT NULL,
                                                           PRIMARY KEY (`id`)
);

-- --------------------------------------------------------

--
-- Table structure for table `collaborator_available`
--

DROP TABLE IF EXISTS `collaborator_available`;
CREATE TABLE IF NOT EXISTS `collaborator_available` (
                                                        `id` int(11) NOT NULL AUTO_INCREMENT,
                                                        `collaborator_id` int(11) NOT NULL,
                                                        `collaborator_availability_id` int(11) NOT NULL,
                                                        `created_at` datetime DEFAULT NULL,
                                                        `updated_at` datetime DEFAULT NULL,
                                                        `removed_at` datetime DEFAULT NULL,
                                                        PRIMARY KEY (`id`),
                                                        KEY `fk_collaborator_available_collaborator_idx` (`collaborator_id`),
                                                        KEY `fk_collaborator_available_collaborator_availability_idx` (`collaborator_availability_id`)
);

-- --------------------------------------------------------

--
-- Table structure for table `distro`
--

DROP TABLE IF EXISTS `distro`;
CREATE TABLE IF NOT EXISTS `distro` (
                                        `id` int(11) NOT NULL AUTO_INCREMENT,
                                        `name` varchar(80) NOT NULL,
                                        PRIMARY KEY (`id`)
);

-- --------------------------------------------------------

--
-- Table structure for table `edition`
--

DROP TABLE IF EXISTS `edition`;
CREATE TABLE IF NOT EXISTS `edition` (
                                         `id` int(11) NOT NULL AUTO_INCREMENT,
                                         `year` char(4) NOT NULL,
                                         `active` smallint(1) DEFAULT NULL,
                                         `created_at` datetime DEFAULT NULL,
                                         `updated_at` datetime DEFAULT NULL,
                                         `removed_at` datetime DEFAULT NULL,
                                         PRIMARY KEY (`id`)
);

-- --------------------------------------------------------

--
-- Table structure for table `participant`
--

DROP TABLE IF EXISTS `participant`;
CREATE TABLE IF NOT EXISTS `participant` (
                                             `id` int(11) NOT NULL AUTO_INCREMENT,
                                             `edition_id` int(11) NOT NULL,
                                             `person_id` int(11) NOT NULL,
                                             `presented_at` datetime DEFAULT NULL,
                                             `prizedraw_confirmation_at` datetime DEFAULT NULL,
                                             `prizedraw_winner` datetime DEFAULT NULL,
                                             `prizedraw_order` int(11) DEFAULT NULL,
                                             `prizedraw_description` text DEFAULT NULL,
                                             `created_at` datetime DEFAULT NULL,
                                             `updated_at` datetime DEFAULT NULL,
                                             `removed_at` datetime DEFAULT NULL,
                                             PRIMARY KEY (`id`),
                                             KEY `fk_participant_edition_idx` (`edition_id`),
                                             KEY `fk_participant_person_idx` (`person_id`)
);

-- --------------------------------------------------------

--
-- Table structure for table `person`
--

DROP TABLE IF EXISTS `person`;
CREATE TABLE IF NOT EXISTS `person` (
                                        `id` int(11) NOT NULL AUTO_INCREMENT,
                                        `name` varchar(80) NOT NULL,
                                        `federal_code` char(14) DEFAULT NULL,
                                        `email` varchar(40) NOT NULL,
                                        `phone` varchar(40) NOT NULL,
                                        `photo` text DEFAULT NULL,
                                        `bio` text DEFAULT NULL,
                                        `site` varchar(100) DEFAULT NULL,
                                        `use_free` smallint(1) DEFAULT NULL,
                                        `distro_id` int(11) DEFAULT NULL,
                                        `student_info_id` int(11) DEFAULT NULL,
                                        `student_place` varchar(100) DEFAULT NULL,
                                        `student_course` varchar(100) DEFAULT NULL,
                                        `address_state` char(2) DEFAULT NULL,
                                        `created_at` datetime DEFAULT NULL,
                                        `updated_at` datetime DEFAULT NULL,
                                        `removed_at` datetime DEFAULT NULL,
                                        PRIMARY KEY (`id`),
                                        KEY `fk_person_distro_idx` (`distro_id`),
                                        KEY `fk_person_student_info_idx` (`student_info_id`)
);

-- --------------------------------------------------------

--
-- Table structure for table `speaker_talk`
--

DROP TABLE IF EXISTS `speaker_talk`;
CREATE TABLE IF NOT EXISTS `speaker_talk` (
                                              `id` int(11) NOT NULL AUTO_INCREMENT,
                                              `speaker_id` int(11) NOT NULL,
                                              `talk_id` int(11) NOT NULL,
                                              `created_at` datetime DEFAULT NULL,
                                              `updated_at` datetime DEFAULT NULL,
                                              `removed_at` datetime DEFAULT NULL,
                                              PRIMARY KEY (`id`),
                                              KEY `fk_speaker_talk_speaker_idx` (`speaker_id`),
                                              KEY `fk_speaker_talk_talk_idx` (`talk_id`)
);

-- --------------------------------------------------------

--
-- Table structure for table `states`
--

DROP TABLE IF EXISTS `states`;
CREATE TABLE IF NOT EXISTS `states` (
                                        `id` int(11) NOT NULL AUTO_INCREMENT,
                                        `name` varchar(80) NOT NULL,
                                        `acronym` char(2) NOT NULL,
                                        `created_at` datetime DEFAULT NULL,
                                        `updated_at` datetime DEFAULT NULL,
                                        `removed_at` datetime DEFAULT NULL,
                                        PRIMARY KEY (`id`)
);

-- --------------------------------------------------------

--
-- Table structure for table `student_info`
--

DROP TABLE IF EXISTS `student_info`;
CREATE TABLE IF NOT EXISTS `student_info` (
                                              `id` int(11) NOT NULL AUTO_INCREMENT,
                                              `name` varchar(60) NOT NULL,
                                              PRIMARY KEY (`id`)
);

-- --------------------------------------------------------

--
-- Table structure for table `talk`
--

DROP TABLE IF EXISTS `talk`;
CREATE TABLE IF NOT EXISTS `talk` (
                                      `id` int(11) NOT NULL AUTO_INCREMENT,
                                      `edition_id` int(11) NOT NULL,
                                      `title` varchar(80) NOT NULL,
                                      `description` text NOT NULL,
                                      `shift` char(1) NOT NULL,
                                      `kind` char(1) NOT NULL,
                                      `talk_subject_id` int(11) NOT NULL,
                                      `slide_file` varchar(255) DEFAULT NULL,
                                      `slide_url` varchar(100) DEFAULT NULL,
                                      `audited_at` datetime DEFAULT NULL,
                                      `audit_note` text DEFAULT NULL,
                                      `approved` smallint(1) DEFAULT NULL,
                                      `confirmed_at` datetime DEFAULT NULL,
                                      `created_at` datetime DEFAULT NULL,
                                      `updated_at` datetime DEFAULT NULL,
                                      `removed_at` datetime DEFAULT NULL,
                                      PRIMARY KEY (`id`),
                                      KEY `fk_talk_edition_idx` (`edition_id`),
                                      KEY `fk_talk_talk_subject_idx` (`talk_subject_id`)
);

-- --------------------------------------------------------

--
-- Table structure for table `talk_subject`
--

DROP TABLE IF EXISTS `talk_subject`;
CREATE TABLE IF NOT EXISTS `talk_subject` (
                                              `id` int(11) NOT NULL AUTO_INCREMENT,
                                              `name` varchar(80) NOT NULL,
                                              PRIMARY KEY (`id`)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_collaborator`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `vw_collaborator`;
CREATE TABLE IF NOT EXISTS `vw_collaborator` (
                                                 `year` char(4)
    ,`name` varchar(80)
    ,`email` varchar(40)
    ,`phone` varchar(40)
    ,`student_place` varchar(100)
    ,`areas` mediumtext
    ,`availabilities` mediumtext
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_participant`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `vw_participant`;
CREATE TABLE IF NOT EXISTS `vw_participant` (
                                                `year` char(4)
    ,`participant_id` int(11)
    ,`person_id` int(11)
    ,`name` varchar(80)
    ,`email` varchar(40)
    ,`phone` varchar(40)
    ,`presented_at` datetime
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_participant_presented`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `vw_participant_presented`;
CREATE TABLE IF NOT EXISTS `vw_participant_presented` (
                                                          `year` char(4)
    ,`participant_id` int(11)
    ,`person_id` int(11)
    ,`name` varchar(80)
    ,`email` varchar(40)
    ,`phone` varchar(40)
    ,`presented_at` datetime
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_talk`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `vw_talk`;
CREATE TABLE IF NOT EXISTS `vw_talk` (
                                         `year` char(4)
    ,`talk_subject` varchar(80)
    ,`talk_id` int(11)
    ,`title` varchar(80)
    ,`description` text
    ,`shift` varchar(5)
    ,`kind` varchar(8)
    ,`person_id` int(11)
    ,`name` varchar(80)
    ,`email` varchar(40)
    ,`phone` varchar(40)
);

-- --------------------------------------------------------

--
-- Structure for view `vw_collaborator`
--
DROP TABLE IF EXISTS `vw_collaborator`;

DROP VIEW IF EXISTS `vw_collaborator`;
CREATE VIEW `vw_collaborator`  AS SELECT `e`.`year` AS `year`, `p`.`name` AS `name`, `p`.`email` AS `email`, `p`.`phone` AS `phone`, `p`.`student_place` AS `student_place`, (select group_concat(`ca2`.`name` separator '; ') from (`collaborator_area` `ca` join `collaboration_area` `ca2` on(`ca`.`collaboration_area_id` = `ca2`.`id`)) where `ca`.`collaborator_id` = `c`.`id`) AS `areas`, (select group_concat(`ca3`.`name` separator '; ') from (`collaborator_available` `ca1` join `collaborator_availability` `ca3` on(`ca1`.`collaborator_availability_id` = `ca3`.`id`)) where `ca1`.`collaborator_id` = `c`.`id`) AS `availabilities` FROM ((`collaborator` `c` join `person` `p` on(`p`.`id` = `c`.`person_id`)) join `edition` `e` on(`e`.`id` = `c`.`edition_id`)) ORDER BY `e`.`year` DESC, `p`.`name` ASC  ;

-- --------------------------------------------------------

--
-- Structure for view `vw_participant`
--
DROP TABLE IF EXISTS `vw_participant`;

DROP VIEW IF EXISTS `vw_participant`;
CREATE VIEW `vw_participant`  AS SELECT `e`.`year` AS `year`, `pt`.`id` AS `participant_id`, `p`.`id` AS `person_id`, `p`.`name` AS `name`, `p`.`email` AS `email`, `p`.`phone` AS `phone`, `pt`.`presented_at` AS `presented_at` FROM ((`participant` `pt` join `person` `p` on(`pt`.`person_id` = `p`.`id`)) join `edition` `e` on(`e`.`id` = `pt`.`edition_id`)) ORDER BY `e`.`year` DESC, `p`.`name` ASC  ;

-- --------------------------------------------------------

--
-- Structure for view `vw_participant_presented`
--
DROP TABLE IF EXISTS `vw_participant_presented`;

DROP VIEW IF EXISTS `vw_participant_presented`;
CREATE VIEW `vw_participant_presented`  AS SELECT `vw_participant`.`year` AS `year`, `vw_participant`.`participant_id` AS `participant_id`, `vw_participant`.`person_id` AS `person_id`, `vw_participant`.`name` AS `name`, `vw_participant`.`email` AS `email`, `vw_participant`.`phone` AS `phone`, `vw_participant`.`presented_at` AS `presented_at` FROM `vw_participant` WHERE `vw_participant`.`presented_at` is not nullnot null  ;

-- --------------------------------------------------------

--
-- Structure for view `vw_talk`
--
DROP TABLE IF EXISTS `vw_talk`;

DROP VIEW IF EXISTS `vw_talk`;
CREATE VIEW `vw_talk`  AS SELECT `e`.`year` AS `year`, `ts`.`name` AS `talk_subject`, `t`.`id` AS `talk_id`, `t`.`title` AS `title`, `t`.`description` AS `description`, CASE `t`.`shift` WHEN 'M' THEN 'Manhã' ELSE 'Tarde' END AS `shift`, CASE `t`.`kind` WHEN 'O' THEN 'Oficina' ELSE 'Palestra' END AS `kind`, `p`.`id` AS `person_id`, `p`.`name` AS `name`, `p`.`email` AS `email`, `p`.`phone` AS `phone` FROM ((((`talk` `t` join `talk_subject` `ts` on(`ts`.`id` = `t`.`talk_subject_id`)) join `speaker_talk` `st` on(`st`.`talk_id` = `t`.`id`)) join `person` `p` on(`p`.`id` = `st`.`speaker_id`)) join `edition` `e` on(`e`.`id` = `t`.`edition_id`)) WHERE `t`.`id` <> 35 ORDER BY `e`.`year` DESC, `ts`.`name` ASC, `t`.`title` ASC  ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `collaborator`
--
ALTER TABLE `collaborator`
    ADD CONSTRAINT `fk_collaborator_edition` FOREIGN KEY (`edition_id`) REFERENCES `edition` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    ADD CONSTRAINT `fk_collaborator_person` FOREIGN KEY (`person_id`) REFERENCES `person` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `collaborator_area`
--
ALTER TABLE `collaborator_area`
    ADD CONSTRAINT `fk_collaborator_area_collaboration_area` FOREIGN KEY (`collaboration_area_id`) REFERENCES `collaboration_area` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    ADD CONSTRAINT `fk_collaborator_area_collaborator` FOREIGN KEY (`collaborator_id`) REFERENCES `collaborator` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `collaborator_available`
--
ALTER TABLE `collaborator_available`
    ADD CONSTRAINT `fk_collaborator_available_collaborator` FOREIGN KEY (`collaborator_id`) REFERENCES `collaborator` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    ADD CONSTRAINT `fk_collaborator_available_collaborator_availability` FOREIGN KEY (`collaborator_availability_id`) REFERENCES `collaborator_availability` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `participant`
--
ALTER TABLE `participant`
    ADD CONSTRAINT `fk_participant_edition` FOREIGN KEY (`edition_id`) REFERENCES `edition` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    ADD CONSTRAINT `fk_participant_person` FOREIGN KEY (`person_id`) REFERENCES `person` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `person`
--
ALTER TABLE `person`
    ADD CONSTRAINT `fk_person_distro` FOREIGN KEY (`distro_id`) REFERENCES `distro` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    ADD CONSTRAINT `fk_person_student_info` FOREIGN KEY (`student_info_id`) REFERENCES `student_info` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `speaker_talk`
--
ALTER TABLE `speaker_talk`
    ADD CONSTRAINT `fk_speaker_talk_speaker` FOREIGN KEY (`speaker_id`) REFERENCES `person` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    ADD CONSTRAINT `fk_speaker_talk_talk` FOREIGN KEY (`talk_id`) REFERENCES `talk` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `talk`
--
ALTER TABLE `talk`
    ADD CONSTRAINT `fk_talk_edition` FOREIGN KEY (`edition_id`) REFERENCES `edition` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    ADD CONSTRAINT `fk_talk_talk_subject` FOREIGN KEY (`talk_subject_id`) REFERENCES `talk_subject` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

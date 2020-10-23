-- Adminer 4.7.7 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

DROP DATABASE IF EXISTS `db_semestralka_vaii`;
CREATE DATABASE `db_semestralka_vaii` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `db_semestralka_vaii`;

DROP TABLE IF EXISTS `externisti`;
CREATE TABLE `externisti` (
  `id_osoba` int NOT NULL AUTO_INCREMENT,
  `id_firma` int NOT NULL,
  PRIMARY KEY (`id_osoba`),
  KEY `externisti_firmy_id_firma_fk` (`id_firma`),
  CONSTRAINT `externisti_firmy_id_firma_fk` FOREIGN KEY (`id_firma`) REFERENCES `firmy` (`id_firma`),
  CONSTRAINT `externisti_os_udaje_id_osoba_fk` FOREIGN KEY (`id_osoba`) REFERENCES `os_udaje` (`id_osoba`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


DROP TABLE IF EXISTS `fakulty`;
CREATE TABLE `fakulty` (
  `id_fakulta` int NOT NULL AUTO_INCREMENT,
  `nazov` varchar(55) NOT NULL,
  `skratka` varchar(10) NOT NULL,
  PRIMARY KEY (`id_fakulta`),
  UNIQUE KEY `fakulty_nazov_uindex` (`nazov`),
  UNIQUE KEY `fakulty_skratka_uindex` (`skratka`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `fakulty` (`id_fakulta`, `nazov`, `skratka`) VALUES
(1,	'Fakulta prevádzky a ekonomiky dopravy a spojov',	'PEDAS'),
(2,	'Strojnícka fakulta',	'FSTROJ'),
(3,	'Fakulta elektrotechniky a informačných technológií',	'FEIT'),
(4,	'Stavebná fakulta',	'SVF'),
(5,	'Fakulta bezpečnostného inžinierstva',	'FBI'),
(6,	'Fakulta riadenia a informatiky',	'FRI'),
(7,	'Fakulta humanitných vied',	'FHV')
ON DUPLICATE KEY UPDATE `id_fakulta` = VALUES(`id_fakulta`), `nazov` = VALUES(`nazov`), `skratka` = VALUES(`skratka`);

DROP TABLE IF EXISTS `firmy`;
CREATE TABLE `firmy` (
  `id_firma` int NOT NULL AUTO_INCREMENT,
  `nazov` varchar(60) NOT NULL,
  `adresa` varchar(80) NOT NULL,
  PRIMARY KEY (`id_firma`),
  UNIQUE KEY `firmy_adresa_uindex` (`adresa`),
  UNIQUE KEY `firmy_nazov_uindex` (`nazov`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `firmy` (`id_firma`, `nazov`, `adresa`) VALUES
(1,	'GlobalLogic s.r.o.',	'Antona Bernoláka 3334/72, 010 01 Žilina'),
(2,	'Avast',	'Poštová 3049/1, 010 08 Žilina-Vlčince'),
(3,	'Accenture',	'Plynárenská 7/C, 821 09 Bratislava'),
(4,	'BrainIT',	'Veľký diel 3323, 010 08 Žilina'),
(5,	'IBM Slovensko, s.r.o.',	'Krasovského 14, 851 01 Petržalka'),
(6,	'KROS a.s.',	'Alexandra Rudnaya 21, 010 01 Žilina'),
(7,	'Scheidt & Bachmann Slovensko s.r.o.',	'Priemyselná 14, 010 01 Žilina'),
(8,	'GoodRequest s.r.o.',	'Framborská 58, 010 01 Žilina'),
(9,	'Siemens, s.r.o.',	'Lamačská cesta 6257/3A, 841 04 Karlova Ves'),
(10,	'Softec, s.r.o.',	'Jarošova 2961/1, 831 03 Bratislava')
ON DUPLICATE KEY UPDATE `id_firma` = VALUES(`id_firma`), `nazov` = VALUES(`nazov`), `adresa` = VALUES(`adresa`);

DROP TABLE IF EXISTS `katedry`;
CREATE TABLE `katedry` (
  `id_katedra` int NOT NULL AUTO_INCREMENT,
  `nazov` varchar(100) NOT NULL,
  `skratka` varchar(10) NOT NULL,
  `id_fakulta` int NOT NULL,
  PRIMARY KEY (`id_katedra`),
  UNIQUE KEY `katedra_nazov_katedry_uindex` (`nazov`),
  UNIQUE KEY `katedra_skratka_uindex` (`skratka`),
  KEY `katedry_fakulty_id_fakulta_fk` (`id_fakulta`),
  CONSTRAINT `katedry_fakulty_id_fakulta_fk` FOREIGN KEY (`id_fakulta`) REFERENCES `fakulty` (`id_fakulta`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `katedry` (`id_katedra`, `nazov`, `skratka`, `id_fakulta`) VALUES
(1,	'Katedra informačných sietí',	'KIS',	6),
(2,	'Katedra informatiky',	'KI',	6),
(3,	'Katedra manažérských teórií',	'KMNT',	6),
(4,	'Katedra makro a mikroekonomiky',	'KMME',	6),
(5,	'Katedra matematických metód a operačnej analýzy',	'KMMOA',	6),
(6,	'Katedra softvérových technológií',	'KST',	6),
(7,	'Katedra technickej kybernetiky',	'KTK',	6)
ON DUPLICATE KEY UPDATE `id_katedra` = VALUES(`id_katedra`), `nazov` = VALUES(`nazov`), `skratka` = VALUES(`skratka`), `id_fakulta` = VALUES(`id_fakulta`);

DROP TABLE IF EXISTS `oblubene_temy`;
CREATE TABLE `oblubene_temy` (
  `id_tema` int NOT NULL,
  `id_student` int NOT NULL,
  PRIMARY KEY (`id_tema`,`id_student`),
  KEY `oblubene_temy_studenti_id_osoba_fk` (`id_student`),
  CONSTRAINT `oblubene_temy_studenti_id_osoba_fk` FOREIGN KEY (`id_student`) REFERENCES `studenti` (`id_osoba`),
  CONSTRAINT `oblubene_temy_zaver_prace_id_tema_fk` FOREIGN KEY (`id_tema`) REFERENCES `zaver_prace` (`id_tema`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


DROP TABLE IF EXISTS `odbory`;
CREATE TABLE `odbory` (
  `id_odbor` int NOT NULL AUTO_INCREMENT,
  `id_fakulta` int NOT NULL,
  `nazov` varchar(50) NOT NULL,
  PRIMARY KEY (`id_odbor`),
  UNIQUE KEY `odbory_nazov_uindex` (`nazov`),
  KEY `odbory_fakulty_id_fakulta_fk` (`id_fakulta`),
  CONSTRAINT `odbory_fakulty_id_fakulta_fk` FOREIGN KEY (`id_fakulta`) REFERENCES `fakulty` (`id_fakulta`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `odbory` (`id_odbor`, `id_fakulta`, `nazov`) VALUES
(1,	6,	'informatika'),
(2,	6,	'manažment'),
(3,	6,	'počítačové inžinierstvo'),
(4,	6,	'informatika a riadenie'),
(5,	6,	'informačné a sieťové technológie'),
(6,	6,	'informačný manažment'),
(7,	6,	'biomedicínska informatika'),
(8,	6,	'inteligentné informačné systémy'),
(9,	6,	'aplikované sieťové inžinierstvo'),
(14,	6,	'informačné systémy - podniková informatika'),
(15,	6,	'informačné systémy - spracovanie dát'),
(16,	6,	'informačné systémy - grafické spracovanie dát')
ON DUPLICATE KEY UPDATE `id_odbor` = VALUES(`id_odbor`), `id_fakulta` = VALUES(`id_fakulta`), `nazov` = VALUES(`nazov`);

DROP TABLE IF EXISTS `os_udaje`;
CREATE TABLE `os_udaje` (
  `id_osoba` int NOT NULL AUTO_INCREMENT,
  `id_titul_pred` int DEFAULT NULL,
  `id_titul_za` int DEFAULT NULL,
  `id_rola` int NOT NULL,
  `meno` varchar(45) NOT NULL,
  `email` varchar(50) NOT NULL,
  `os_cislo` int NOT NULL,
  `password_hash` varchar(60) NOT NULL,
  `telefon` int DEFAULT NULL,
  `vytvorenie` datetime NOT NULL,
  `upravenie` datetime DEFAULT NULL,
  PRIMARY KEY (`id_osoba`),
  UNIQUE KEY `osoba_email_uindex` (`email`),
  UNIQUE KEY `os_udaje_os_cislo_uindex` (`os_cislo`),
  UNIQUE KEY `osoba_telefon_uindex` (`telefon`),
  KEY `os_udaje_tituly_id_titul_fk` (`id_titul_pred`),
  KEY `os_udaje_tituly_id_titul_fk_2` (`id_titul_za`),
  KEY `os_udaje_role_id_rola_fk` (`id_rola`),
  CONSTRAINT `os_udaje_role_id_rola_fk` FOREIGN KEY (`id_rola`) REFERENCES `role` (`id_rola`),
  CONSTRAINT `os_udaje_tituly_id_titul_fk` FOREIGN KEY (`id_titul_pred`) REFERENCES `tituly` (`id_titul`),
  CONSTRAINT `os_udaje_tituly_id_titul_fk_2` FOREIGN KEY (`id_titul_za`) REFERENCES `tituly` (`id_titul`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `os_udaje` (`id_osoba`, `id_titul_pred`, `id_titul_za`, `id_rola`, `meno`, `email`, `os_cislo`, `password_hash`, `telefon`, `vytvorenie`, `upravenie`) VALUES
(1,	1,	7,	1,	'Štefan Toth',	'stefan.toth@fri.uniza.sk',	123456,	'$2y$10$8e5px1vlJPgMiZd02IZwOOvZD9bmRlhkyFsxSkhIw2Bpx3GZ5NoaG',	123456789,	'2020-10-12 22:51:39',	NULL),
(2,	13,	13,	2,	'Adam Vysoký',	'Adam.Vysoky@stud.uniza.sk',	654321,	'$2y$10$8e5px1vlJPgMiZd02IZwOOvZD9bmRlhkyFsxSkhIw2Bpx3GZ5NoaG',	987654321,	'2020-10-14 11:44:17',	NULL),
(3,	9,	7,	1,	'Miroslav Kvaššay',	'Miroslav.Kvassay@fri.uniza.sk',	168724,	'$2y$10$8e5px1vlJPgMiZd02IZwOOvZD9bmRlhkyFsxSkhIw2Bpx3GZ5NoaG',	147258369,	'2020-10-17 00:15:18',	NULL),
(4,	1,	7,	1,	'Jozef Papán',	'Jozef.Papan@fri.uniza.sk',	943876,	'$2y$10$8e5px1vlJPgMiZd02IZwOOvZD9bmRlhkyFsxSkhIw2Bpx3GZ5NoaG',	681892897,	'2020-10-17 00:20:54',	NULL),
(5,	1,	7,	1,	'Michal Ďuračík',	'Michal.Duracik@fri.uniza.sk',	519865,	'$2y$10$8e5px1vlJPgMiZd02IZwOOvZD9bmRlhkyFsxSkhIw2Bpx3GZ5NoaG',	824842465,	'2020-10-17 00:22:31',	NULL),
(6,	9,	7,	1,	'Ján Boháčik',	'Jan.Bohacik@fri.uniza.sk',	864342,	'$2y$10$8e5px1vlJPgMiZd02IZwOOvZD9bmRlhkyFsxSkhIw2Bpx3GZ5NoaG',	482741827,	'2020-10-17 00:24:11',	NULL),
(7,	9,	7,	1,	'Jozef Kostolný',	'Jozef.Kostolny@fri.uniza.sk',	457445,	'$2y$10$8e5px1vlJPgMiZd02IZwOOvZD9bmRlhkyFsxSkhIw2Bpx3GZ5NoaG',	806717886,	'2020-10-17 00:25:44',	NULL),
(8,	1,	7,	1,	'Eva Malichová',	'Eva.Malichova@fri.uniza.sk',	852454,	'$2y$10$8e5px1vlJPgMiZd02IZwOOvZD9bmRlhkyFsxSkhIw2Bpx3GZ5NoaG',	104484554,	'2020-10-17 00:27:21',	NULL),
(9,	1,	7,	1,	'Lucia Pančíková',	'Lucia.Pancikova@fri.uniza.sk',	184741,	'$2y$10$8e5px1vlJPgMiZd02IZwOOvZD9bmRlhkyFsxSkhIw2Bpx3GZ5NoaG',	840486846,	'2020-10-17 00:28:24',	NULL),
(10,	1,	13,	1,	'František Kajánek',	'Frantisek.Kajanek@fri.uniza.sk',	751164,	'$2y$10$8e5px1vlJPgMiZd02IZwOOvZD9bmRlhkyFsxSkhIw2Bpx3GZ5NoaG',	784564485,	'2020-10-17 00:29:41',	NULL),
(11,	9,	7,	1,	'Pavel Segeč',	'Pavel.Segec@fri.uniza.sk',	968462,	'$2y$10$8e5px1vlJPgMiZd02IZwOOvZD9bmRlhkyFsxSkhIw2Bpx3GZ5NoaG',	564181848,	'2020-10-17 00:32:17',	NULL),
(12,	1,	7,	1,	'Marek Moravčík',	'Marek.Moravcik@fri.uniza.sk',	861614,	'$2y$10$8e5px1vlJPgMiZd02IZwOOvZD9bmRlhkyFsxSkhIw2Bpx3GZ5NoaG',	444741417,	'2020-10-17 00:33:15',	NULL),
(13,	13,	13,	2,	'Jakub Senko',	'Jakub.Senko@stud.uniza.sk',	482648,	'$2y$10$8e5px1vlJPgMiZd02IZwOOvZD9bmRlhkyFsxSkhIw2Bpx3GZ5NoaG',	642680482,	'2020-10-17 00:34:36',	NULL),
(14,	13,	13,	2,	'Martin Androvič',	'Martin.Androvic@stud.uniza.sk',	891068,	'$2y$10$8e5px1vlJPgMiZd02IZwOOvZD9bmRlhkyFsxSkhIw2Bpx3GZ5NoaG',	560420445,	'2020-10-17 00:36:25',	NULL),
(15,	13,	13,	2,	'Veronika Vaňová',	'Veronika.Vanova@stud.uniza.sk',	890894,	'$2y$10$8e5px1vlJPgMiZd02IZwOOvZD9bmRlhkyFsxSkhIw2Bpx3GZ5NoaG',	64041405,	'2020-10-17 00:37:37',	NULL),
(16,	13,	13,	2,	'Samuel Peter',	'Samuel.Peter@stud.uniza.sk',	342055,	'$2y$10$8e5px1vlJPgMiZd02IZwOOvZD9bmRlhkyFsxSkhIw2Bpx3GZ5NoaG',	820264026,	'2020-10-17 00:38:29',	NULL),
(17,	13,	13,	2,	'Ľudmila Čániová',	'Ludmila.Caniova@stud.uniza.sk',	610610,	'$2y$10$8e5px1vlJPgMiZd02IZwOOvZD9bmRlhkyFsxSkhIw2Bpx3GZ5NoaG',	820964021,	'2020-10-17 00:39:23',	NULL),
(18,	13,	13,	2,	'Ivana Gabrišová',	'Ivana.Gabrisova@stud.uniza.sk',	600610,	'$2y$10$8e5px1vlJPgMiZd02IZwOOvZD9bmRlhkyFsxSkhIw2Bpx3GZ5NoaG',	20964021,	'2020-10-17 00:40:42',	NULL),
(19,	13,	13,	2,	'Filip Janiga',	'Filip.Janiga@stud.uniza.sk',	610690,	'$2y$10$8e5px1vlJPgMiZd02IZwOOvZD9bmRlhkyFsxSkhIw2Bpx3GZ5NoaG',	920904021,	'2020-10-17 00:41:45',	NULL),
(20,	13,	13,	2,	'Juraj Oberta',	'Juraj.Oberta@stud.uniza.sk',	690690,	'$2y$10$8e5px1vlJPgMiZd02IZwOOvZD9bmRlhkyFsxSkhIw2Bpx3GZ5NoaG',	920904020,	'2020-10-17 00:42:30',	NULL),
(21,	13,	13,	2,	'Linda Majerčiaková',	'Linda.Majerciakova@stud.uniza.sk',	741395,	'$2y$10$8e5px1vlJPgMiZd02IZwOOvZD9bmRlhkyFsxSkhIw2Bpx3GZ5NoaG',	120904020,	'2020-10-17 00:43:45',	NULL),
(22,	13,	13,	2,	'Dominik Dubovec',	'Dominik.Dubovec@stud.uniza.sk',	941395,	'$2y$10$8e5px1vlJPgMiZd02IZwOOvZD9bmRlhkyFsxSkhIw2Bpx3GZ5NoaG',	820104020,	'2020-10-17 00:44:32',	NULL),
(23,	13,	13,	2,	'Jozef Kompan',	'Jozef.Kompan@stud.uniza.sk',	941305,	'$2y$10$8e5px1vlJPgMiZd02IZwOOvZD9bmRlhkyFsxSkhIw2Bpx3GZ5NoaG',	820104005,	'2020-10-17 00:45:49',	NULL),
(24,	13,	13,	2,	'Patrik Hlinka',	'Patrik.Hlinka@stud.uniza.sk',	981305,	'$2y$10$8e5px1vlJPgMiZd02IZwOOvZD9bmRlhkyFsxSkhIw2Bpx3GZ5NoaG',	864040058,	'2020-10-17 00:46:35',	NULL)
ON DUPLICATE KEY UPDATE `id_osoba` = VALUES(`id_osoba`), `id_titul_pred` = VALUES(`id_titul_pred`), `id_titul_za` = VALUES(`id_titul_za`), `id_rola` = VALUES(`id_rola`), `meno` = VALUES(`meno`), `email` = VALUES(`email`), `os_cislo` = VALUES(`os_cislo`), `password_hash` = VALUES(`password_hash`), `telefon` = VALUES(`telefon`), `vytvorenie` = VALUES(`vytvorenie`), `upravenie` = VALUES(`upravenie`);

DROP TABLE IF EXISTS `role`;
CREATE TABLE `role` (
  `id_rola` int NOT NULL AUTO_INCREMENT,
  `nazov` varchar(15) NOT NULL,
  PRIMARY KEY (`id_rola`),
  UNIQUE KEY `role_nazov_uindex` (`nazov`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `role` (`id_rola`, `nazov`) VALUES
(2,	'student'),
(1,	'ucitel')
ON DUPLICATE KEY UPDATE `id_rola` = VALUES(`id_rola`), `nazov` = VALUES(`nazov`);

DROP TABLE IF EXISTS `skupiny`;
CREATE TABLE `skupiny` (
  `id_skupina` int NOT NULL AUTO_INCREMENT,
  `nazov` char(6) NOT NULL,
  PRIMARY KEY (`id_skupina`),
  UNIQUE KEY `skupiny_nazov_uindex` (`nazov`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `skupiny` (`id_skupina`, `nazov`) VALUES
(10,	'5ZMN11'),
(11,	'5ZMN12'),
(12,	'5ZMN13'),
(13,	'5ZMN21'),
(14,	'5ZMN22'),
(15,	'5ZMN23'),
(16,	'5ZMN31'),
(17,	'5ZMN32'),
(18,	'5ZMN33'),
(1,	'5ZYI11'),
(2,	'5ZYI12'),
(3,	'5ZYI13'),
(4,	'5ZYI21'),
(5,	'5ZYI22'),
(6,	'5ZYI23'),
(7,	'5ZYI31'),
(8,	'5ZYI32'),
(9,	'5ZYI33'),
(19,	'5ZYP11'),
(20,	'5ZYP12'),
(21,	'5ZYP13'),
(22,	'5ZYP21'),
(23,	'5ZYP22'),
(24,	'5ZYP23'),
(25,	'5ZYP31'),
(26,	'5ZYP32'),
(27,	'5ZYP33')
ON DUPLICATE KEY UPDATE `id_skupina` = VALUES(`id_skupina`), `nazov` = VALUES(`nazov`);

DROP TABLE IF EXISTS `stavy_prace`;
CREATE TABLE `stavy_prace` (
  `id_stav` int NOT NULL AUTO_INCREMENT,
  `stav` varchar(20) NOT NULL,
  PRIMARY KEY (`id_stav`),
  UNIQUE KEY `stavy_prace_stav_uindex` (`stav`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `stavy_prace` (`id_stav`, `stav`) VALUES
(1,	'nepriradené'),
(2,	'priradené'),
(3,	'skončené')
ON DUPLICATE KEY UPDATE `id_stav` = VALUES(`id_stav`), `stav` = VALUES(`stav`);

DROP TABLE IF EXISTS `studenti`;
CREATE TABLE `studenti` (
  `id_osoba` int NOT NULL AUTO_INCREMENT,
  `id_skupina` int NOT NULL,
  `id_odbor` int NOT NULL,
  PRIMARY KEY (`id_osoba`),
  KEY `studenti_odbory_id_odbor_fk` (`id_odbor`),
  KEY `studenti_skupiny_id_skupina_fk` (`id_skupina`),
  CONSTRAINT `studenti_odbory_id_odbor_fk` FOREIGN KEY (`id_odbor`) REFERENCES `odbory` (`id_odbor`),
  CONSTRAINT `studenti_os_udaje_id_osoba_fk` FOREIGN KEY (`id_osoba`) REFERENCES `os_udaje` (`id_osoba`),
  CONSTRAINT `studenti_skupiny_id_skupina_fk` FOREIGN KEY (`id_skupina`) REFERENCES `skupiny` (`id_skupina`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `studenti` (`id_osoba`, `id_skupina`, `id_odbor`) VALUES
(2,	1,	1),
(13,	18,	1),
(14,	11,	2),
(15,	22,	1),
(16,	13,	3),
(17,	5,	1),
(18,	1,	2),
(19,	24,	3),
(20,	14,	1),
(21,	27,	1),
(22,	22,	3),
(23,	12,	2),
(24,	5,	1)
ON DUPLICATE KEY UPDATE `id_osoba` = VALUES(`id_osoba`), `id_skupina` = VALUES(`id_skupina`), `id_odbor` = VALUES(`id_odbor`);

DROP TABLE IF EXISTS `tituly`;
CREATE TABLE `tituly` (
  `id_titul` int NOT NULL AUTO_INCREMENT,
  `nazov` varchar(10) NOT NULL,
  PRIMARY KEY (`id_titul`),
  UNIQUE KEY `tituly_nazov_uindex` (`nazov`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `tituly` (`id_titul`, `nazov`) VALUES
(13,	''),
(6,	'Bc.'),
(8,	'CSc.'),
(9,	'doc. Ing.'),
(11,	'doc. PhDr.'),
(10,	'doc. RNDr.'),
(1,	'Ing.'),
(5,	'JUDr.'),
(2,	'Mgr.'),
(7,	'PhD.'),
(4,	'PhDr.'),
(12,	'prof. Ing.'),
(3,	'RNDr.')
ON DUPLICATE KEY UPDATE `id_titul` = VALUES(`id_titul`), `nazov` = VALUES(`nazov`);

DROP TABLE IF EXISTS `typy_prac`;
CREATE TABLE `typy_prac` (
  `id_typ` int NOT NULL AUTO_INCREMENT,
  `nazov` varchar(20) NOT NULL,
  `skratka` varchar(5) NOT NULL,
  PRIMARY KEY (`id_typ`),
  UNIQUE KEY `typ_prace_nazov_uindex` (`nazov`),
  UNIQUE KEY `typ_prace_skratka_uindex` (`skratka`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `typy_prac` (`id_typ`, `nazov`, `skratka`) VALUES
(1,	'Bakalárska práca',	'BC'),
(2,	'Diplomová práca',	'DP'),
(3,	'Dizertačná práca',	'DZ')
ON DUPLICATE KEY UPDATE `id_typ` = VALUES(`id_typ`), `nazov` = VALUES(`nazov`), `skratka` = VALUES(`skratka`);

DROP TABLE IF EXISTS `ucitelia`;
CREATE TABLE `ucitelia` (
  `id_osoba` int NOT NULL AUTO_INCREMENT,
  `id_katedra` int NOT NULL,
  `miestnost` char(5) DEFAULT NULL,
  `volna_kapacita` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id_osoba`),
  KEY `ucitelia_katedry_id_katedra_fk` (`id_katedra`),
  CONSTRAINT `ucitelia_katedry_id_katedra_fk` FOREIGN KEY (`id_katedra`) REFERENCES `katedry` (`id_katedra`),
  CONSTRAINT `ucitelia_os_udaje_id_osoba_fk` FOREIGN KEY (`id_osoba`) REFERENCES `os_udaje` (`id_osoba`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `ucitelia` (`id_osoba`, `id_katedra`, `miestnost`, `volna_kapacita`) VALUES
(1,	6,	'RA124',	1),
(3,	2,	'RA876',	0),
(4,	1,	'RA672',	1),
(5,	6,	'RA258',	0),
(6,	2,	'RA123',	1),
(7,	2,	'RA456',	0),
(8,	4,	'RB056',	1),
(9,	4,	'RA156',	0),
(10,	6,	'RB576',	1),
(11,	1,	'RA964',	1),
(12,	1,	'RA224',	0)
ON DUPLICATE KEY UPDATE `id_osoba` = VALUES(`id_osoba`), `id_katedra` = VALUES(`id_katedra`), `miestnost` = VALUES(`miestnost`), `volna_kapacita` = VALUES(`volna_kapacita`);

DROP TABLE IF EXISTS `zaver_prace`;
CREATE TABLE `zaver_prace` (
  `id_tema` int NOT NULL AUTO_INCREMENT,
  `id_student` int DEFAULT NULL,
  `id_veduci` int NOT NULL,
  `id_typ` int NOT NULL,
  `id_stav` int NOT NULL DEFAULT '1',
  `id_mentor` int DEFAULT NULL,
  `nazov_sk` varchar(200) NOT NULL,
  `nazov_en` varchar(300) NOT NULL,
  `popis` varchar(2000) NOT NULL,
  `vytvorenie` datetime NOT NULL,
  `upravenie` datetime DEFAULT NULL,
  PRIMARY KEY (`id_tema`),
  UNIQUE KEY `zaver_prace_id_student_uindex` (`id_student`),
  KEY `zaver_prace_os_udaje_id_osoba_fk` (`id_mentor`),
  KEY `zaver_prace_typy_prac_id_typ_fk` (`id_typ`),
  KEY `zaver_prace_ucitelia_id_osoba_fk` (`id_veduci`),
  CONSTRAINT `zaver_prace_os_udaje_id_osoba_fk` FOREIGN KEY (`id_mentor`) REFERENCES `os_udaje` (`id_osoba`),
  CONSTRAINT `zaver_prace_studenti_id_osoba_fk` FOREIGN KEY (`id_student`) REFERENCES `studenti` (`id_osoba`),
  CONSTRAINT `zaver_prace_typy_prac_id_typ_fk` FOREIGN KEY (`id_typ`) REFERENCES `typy_prac` (`id_typ`),
  CONSTRAINT `zaver_prace_ucitelia_id_osoba_fk` FOREIGN KEY (`id_veduci`) REFERENCES `ucitelia` (`id_osoba`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `zaver_prace` (`id_tema`, `id_student`, `id_veduci`, `id_typ`, `id_stav`, `id_mentor`, `nazov_sk`, `nazov_en`, `popis`, `vytvorenie`, `upravenie`) VALUES
(3,	18,	10,	1,	3,	NULL,	'Extrakcia čistého textu z PDF dokumentov',	'Plain text extraction from PDF documents',	'Vytvoriť aplikáciu, ktorá umožní extrahovať čistý text z rôznych PDF dokumentov tak, aby výsledný text neobsahoval obrázky, grafy, tabuľky, opakujúce sa častí dokumentu (hlavičky, pätičky), čísla strán a rôzne chyby spôsobené napr. formátovaním (viaceré stĺpce textov v dokumente, rozdeľovanie slov na konci riadkov s použitím spojovníka).',	'2020-10-16 14:22:14',	NULL),
(4,	NULL,	5,	2,	1,	NULL,	'Jednoduchý CMS hostovateľný na github pages',	'Simple CMS hosted on github pages',	'Cieľom práce je preskúmať možnosti využitia github pages na hostovanie jednoduchých aplikácií. CMS bude implementovaný v jazyku C# s využitím technológie Blazor.',	'2020-10-17 00:58:24',	NULL),
(5,	NULL,	12,	1,	1,	NULL,	'IS pre kino',	'Cinema information system',	'Cieľom práce je návrh a implementácia vlastného univerzálneho riešenia informačného systému pre kino - evidencia programu kina, evidencia jednotlivých sál spolu so štruktúrou a organizáciou sedadiel. Súčasťou riešenia bude možnosť rezervácie a nákup lístkov (podľa zadaných kritérií), vlastné rozhranie pre reporty a štatistiky.',	'2020-10-17 01:00:40',	NULL),
(6,	13,	11,	2,	2,	NULL,	'Kybernetická bezpečnosť z pohľadu zákona a z pohľadu noriem',	'Cybersecurity from the point of view of the law and from the point of view of standards',	'Vytvoriť mapovaciu tabuľku medzi bezpečnostnými opatreniami z vyhlášky 368/2018 zákona o kybernetickej bezpečnosti 69/2018 a opatreniami a odporúčaniami danými v normách a štandardoch pre riadenie informačnej bezpečnosti v organizácii.',	'2020-10-17 01:01:35',	NULL),
(7,	21,	8,	1,	2,	NULL,	'Logická hra pre stolový počítač',	'Desktop logic game',	'Cieľom bakalárskej práce je vytvoriť logickú hru typu „killer sudoku“ pre stolový počítač.',	'2020-10-17 01:02:04',	NULL),
(8,	NULL,	4,	1,	1,	NULL,	'Logistický softvér pre spoločnosť Schaeffler',	'The logistic software for Schaeffler company',	'Návrh a implementácia softvéru pre logistiku Schaeffler Kysuce založený na aktuálnom stave procesov kontrolovania a manipulácie s tovarmi, paletami na sklade.',	'2020-10-17 01:02:23',	NULL),
(9,	24,	12,	1,	2,	NULL,	'Matematické heuristiky pre zovšeobecnenú priraďovaciu úlohu',	'Matheuristics for the generalized assignment problem',	'Cieľom práce je experimentálne porovnať efektivitu dvoch približných algoritmov pre riešenie zovšeobecnenej priraďovacej úlohy. Zovšeobecnená priraďovacia úloha je optimalizačná úloha, v ktorej treba priradiť n úloh m agentom tak, aby zisk vyplývajúci z priradenia bol čo najväčší a aby sa neprekročila kapacita agentov. Požiadavka úlohy sa mení v závislosti od agenta, ktorý ju má vykonať. Úloha je NP-ťažká, preto väčšie prípady nemožno riešiť optimálne a hľadajú sa približné (heuristické) metódy na jej riešenie. Úlohou študenta je implementovať dve približné metódy, ktoré na riešenie podúlohy využívajú solver matematického programovania, a porovnať ich z hľadiska doby výpočtu a kvality riešenia.',	'2020-10-17 01:02:45',	NULL),
(10,	17,	5,	2,	2,	NULL,	'Meranie prenosových charakteristík IP sietí',	'Measurement of IP network parameters',	'Cieľom práce je vykonať merania v sieti Sanet zamerané na získanie prevádzkových parametrov siete medzi vybratými uzlami. Výstupy budú použité pre projekt riešený na KIS. Pri riešení bude použitá technológia IP SLA od fi. Cisco a minimálne jedno riešenie s otvorenou licenciou. Pre riešenie je treba zvládnuť technológiu merania, navrhnúť sady testov so zadefinovaným účelom a vykonať dostatok meraní. Výsledne merania zhodnotiť.',	'2020-10-17 01:03:12',	NULL),
(11,	NULL,	6,	1,	1,	NULL,	'Mobilná aplikácia pre turistov',	'Mobile app for hikers',	'Cieľom práce je vytvoriť mobilnú aplikáciu, ktorá má umožniť vyhľadávanie turistických trás na voľne dostupných mapách (freemap.sk) a poskytovať dôležité informácie pre turistov na trasách (blízkosť verejnej dopravy, ubytovania, stravovania, zaujímavých miest...)',	'2020-10-17 01:03:43',	NULL),
(12,	16,	7,	1,	2,	NULL,	'Mobilná aplikácia: Veterinár po ruke',	'Mobile application for veterinary surgery',	'Cieľom práce je vytvoriť klientsku mobilnú aplikáciu Veterinár po ruke, ktorá slúži ako komunikačný kanál s veterinárom a taktiež ako zoznam zdravotných záznamov o zvieratách užívateľa. Aplikácia prinesie inovatívne metódy vo veterinárnej oblasti, uľahčí prehľad o zdravotnom stave o domácom zvierati, pomôže lepšie sledovať prevenciu a uľahčí lepšie zorganizovať návštevy kliniky.',	'2020-10-17 01:04:09',	NULL),
(13,	NULL,	6,	1,	1,	NULL,	'Vytvorenie webovej aplikácie pre spracovanie údajov elektronického bazáru.',	'Creation of a web application for data processing of electronic bazaar.',	'Cieľom bakalárskej práce je vytvorenie webovej aplikácie pre spracovanie údajov elektronického bazáru. Aplikácia bude evidovať dáta o jednotlivých ponukách zákazníkov a jednotlivých predajoch. Na základe evidovaných dát bude generovať aktuálne ponuky k predaju a realizovať predaje.',	'2020-10-17 01:04:49',	NULL),
(14,	NULL,	9,	2,	1,	NULL,	'Vývoj hier v grafickom engine Unity',	'Game development in the Unity graphics engine',	'Cieľom práce je vytvorenie vlastnej hry v Unity a spracovanie užívateľskej príručky.',	'2020-10-17 01:05:33',	NULL),
(15,	15,	5,	1,	2,	NULL,	'Webový systém na tvorbu grafických rozvrhov',	'Web-based system for creation of graphical schedules',	'Cieľom bakalárskej práce bude návrh a implementácia webovej aplikácie ktorá umožní kolaboratívnu tvorbu grafických rozvrhov. Grafický rozvrh slúži na plánovanie aktivít pri tvorbe sústredení pre deti. Tento rozvrh sa skladá z niekoľkých dní, a každý deň obsahuje plán aktivít daného dňa.',	'2020-10-17 01:06:01',	NULL),
(16,	NULL,	6,	1,	1,	NULL,	'Návrh a vývoj informačného systému pre holičstvo',	'Design and development of an information system for a barber shop',	'Navrhnite a implementujte informačný systém pre holičstvo, ktorý bude: spravovať objednávky, zobrazovať obsadenosť podniku, posielať notifikácie o termínoch, základné CMS pre úpravu statických stránok.',	'2020-10-17 01:06:27',	NULL),
(17,	20,	3,	1,	3,	NULL,	'Ochranné prvky siete novej generácie',	'Next generation Firewalls',	'Práca sa zaoberá analýzou, overením a vyhodnotením ochranných prvkov novej generácie spoločnosti Fortinet.',	'2020-10-17 01:06:48',	NULL)
ON DUPLICATE KEY UPDATE `id_tema` = VALUES(`id_tema`), `id_student` = VALUES(`id_student`), `id_veduci` = VALUES(`id_veduci`), `id_typ` = VALUES(`id_typ`), `id_stav` = VALUES(`id_stav`), `id_mentor` = VALUES(`id_mentor`), `nazov_sk` = VALUES(`nazov_sk`), `nazov_en` = VALUES(`nazov_en`), `popis` = VALUES(`popis`), `vytvorenie` = VALUES(`vytvorenie`), `upravenie` = VALUES(`upravenie`);

-- 2020-10-21 22:54:20

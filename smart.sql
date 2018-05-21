-- SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS `user`;
DROP TABLE IF EXISTS `user_profile`;
DROP TABLE IF EXISTS `user_details`;
DROP TABLE IF EXISTS `institution`;
DROP TABLE IF EXISTS `user_relationship`;
DROP VIEW IF EXISTS user_list;
DROP VIEW IF EXISTS profile_list;
-- SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE IF NOT EXISTS `user` (
	`id` INTEGER PRIMARY KEY AUTOINCREMENT,
	`name` VARCHAR(30) NOT NULL,
	`middle_name` VARCHAR(60),
	`surname` VARCHAR(30) NOT NULL,
	`type` VARCHAR(2),
	`flag` VARCHAR(2)
);

CREATE TABLE IF NOT EXISTS `user_profile` (
	`id` INTEGER PRIMARY KEY AUTOINCREMENT,
	`type` VARCHAR(2),
	`uid` INT,
	`institution` VARCHAR(50)
);

CREATE TABLE IF NOT EXISTS `user_details` (
	`id` INTEGER PRIMARY KEY AUTOINCREMENT,
	`uid` INT NOT NULL,
	`date_of_birth` DATE NOT NULL,
	`national_id` VARCHAR(30),
	`nationality` VARCHAR(20),
	`gender` CHAR,
	`flag` VARCHAR(2),
	`cell` VARCHAR(20),
	`tel` VARCHAR(20),
	`email` VARCHAR(40),
	`password` VARCHAR (100) NOT NULL
);

CREATE TABLE IF NOT EXISTS `institution` (
	`id` INTEGER PRIMARY KEY AUTOINCREMENT,
	`name` VARCHAR(50) NOT NULL,
	`idid` INT NOT NULL
);

CREATE TABLE IF NOT EXISTS `user_relationship` (
	`uid1` INT,
	`uid2` INT,
	`relationship` VARCHAR(20),
	`id` INTEGER PRIMARY KEY AUTOINCREMENT
);

UPDATE sqlite_sequence set seq = 1;

CREATE VIEW user_list AS
SELECT  
	u.`name` || " " || u.`middle_name`|| " "|| u.`surname` AS fullname,
	u.`type`,u.`flag`,
	ud.`id` as udid, ud.`uid`,ud.`date_of_birth`,
	ud.`national_id`, ud.`nationality`,ud.`gender`,ud.`flag`,
	ud.`cell`,ud.`tel`,ud.`email`,ud.`password` as pass_key 
FROM 
	`user` u, `user_details` ud 
WHERE 
	u.id = ud.uid;

CREATE VIEW profile_list AS 
SELECT 
	usp.`type`, usp.`uid`, usp.`id` AS profileid, ur.`id as relate_id,  
	usp.`institution` AS  school , ur.`relationship`
FROM 
	user_profile usp, user_relationship ur 
WHERE
	ur.`uid1` = usp.`uid` OR
	ur.`uid2` = usp.`uid`;

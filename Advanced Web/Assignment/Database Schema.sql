
-- Drop the tables if they are already in the DB
DROP TABLE IF EXISTS shift_table;
DROP TABLE IF EXISTS user_table;
DROP TABLE IF EXISTS level_table;


-- Create the new tables
CREATE TABLE IF NOT EXISTS level_table (
	levelID int NOT NULL AUTO_INCREMENT,
	levelName varchar(100) COLLATE utf8_unicode_ci NOT NULL,
	PRIMARY KEY (levelID),
	INDEX (levelID)
);


CREATE TABLE IF NOT EXISTS user_table (
	userID int NOT NULL AUTO_INCREMENT,
	surname varchar(200) COLLATE utf8_unicode_ci NOT NULL,
	forename varchar(200) COLLATE utf8_unicode_ci NOT NULL,
	password varchar(100) COLLATE utf8_unicode_ci NOT NULL,
	levelID int NOT NULL,
	staffID int NOT NULL,
	PRIMARY KEY (userID),
	INDEX (userID),
	FOREIGN KEY (levelID) REFERENCES level_table (levelID)
);


CREATE TABLE IF NOT EXISTS shift_table (
	shiftID bigint NOT NULL AUTO_INCREMENT,
	userID int NOT NULL,
	shiftDate date NOT NULL,
	PRIMARY KEY (shiftID),
	INDEX (shiftID),
	FOREIGN KEY (userID) REFERENCES user_table (userID)
);

-- Create the Stored Procedures
-- Delimter changes the ending to // instead of ; Allowing ; to be passed in the sproc

DROP PROCEDURE IF EXISTS user_get;
DELIMITER //
CREATE PROCEDURE user_get
(
	IN id int
	)
BEGIN
	SELECT 	u.surname,
			u.forename,
			l.levelName,
			u.levelID,
			u.staffID
	FROM	user_table AS u
			INNER JOIN level_table AS l
	WHERE	u.userID = id;
END //
DELIMITER ;

DROP PROCEDURE IF EXISTS user_getAll;
DELIMITER //
CREATE PROCEDURE user_getAll()
BEGIN
	SELECT 	u.surname,
			u.forename,
			l.levelName,
			u.levelID,
			u.staffID
	FROM	user_table AS u
			INNER JOIN level_table AS l;
END //
DELIMITER ;


DROP PROCEDURE IF EXISTS user_add;
DELIMITER //
CREATE PROCEDURE user_add
(
	IN surnameIN varchar(200),
	IN forenameIN varchar(200),
	IN passwordIN varchar(100),
	IN levelIDIN int,
	IN staffIDIN int
)
BEGIN
	INSERT INTO user_table (
				surname,
				forename,
				password,
				levelID,
				staffID
		)
	VALUES	(
				surnameIN,
				forenameIN,
				passwordIN,
				levelIDIN,
				staffIDIN
		);
END //
DELIMITER ;


DROP PROCEDURE IF EXISTS user_delete;
DELIMITER //
CREATE PROCEDURE user_delete
(
	IN ID int
)
BEGIN
	DELETE FROM user_table
	WHERE	userID = id;
END //
DELIMITER ;


DROP PROCEDURE IF EXISTS user_edit;
DELIMITER //
CREATE PROCEDURE user_edit
(
	IN id int,
	IN surnameIN varchar(200),
	IN forenameIN varchar(200),
	IN passwordIN varchar(100),
	IN levelIDIN int,
	IN staffIDIN int
)
BEGIN
	UPDATE user_table
	SET 	surname = surnameIN,
			forname = forenameIN,
			password = passwordIN,
			levelID = levelIDIN,
			staffID = staffIDIN
	WHERE userID = id;
END //
DELIMITER ;
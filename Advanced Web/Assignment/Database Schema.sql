
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
			INNER JOIN level_table AS l ON l.levelID = u.levelID
	WHERE	u.userID = id;
END //
DELIMITER ;

DROP PROCEDURE IF EXISTS user_getAll;
DELIMITER //
CREATE PROCEDURE user_getAll()
BEGIN
	SELECT DISTINCT u.userID,
					u.surname,
					u.forename,
					l.levelName,
					u.levelID,
					u.staffID
	FROM	user_table AS u
			INNER JOIN level_table AS l ON l.levelID = u.levelID;
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


DROP PROCEDURE IF EXISTS level_add;
DELIMITER //
CREATE PROCEDURE level_add
(
	levelNameIN varchar(100)
)
BEGIN
	INSERT INTO level_table(
			levelName
		)
	VALUES (
			levelNameIN
		);
END //
DELIMITER ;

DROP PROCEDURE IF EXISTS level_edit;
DELIMITER //
CREATE PROCEDURE level_edit
(
	IN levelIDIN int,
	IN levelNameIN varchar(100)
)
BEGIN
	UPDATE 	level_table
	SET 	levelName = levelNameIN
	WHERE	levelID = levelIDIN;
END //
DELIMITER ;

DROP PROCEDURE IF EXISTS level_delete;
DELIMITER //
CREATE PROCEDURE level_delete
(
	IN levelIDIN int
)
BEGIN
	DELETE FROM level_table
	WHERE	levelID = levelIDIN;
END //
DELIMITER ;

DROP PROCEDURE IF EXISTS level_get;
DELIMITER //
CREATE PROCEDURE level_get
(
	IN levelIDIN int
)
BEGIN
	SELECT 	levelName
	FROM	level_table
	WHERE	levelID = levelIDIN;
END
DELIMITER ;

DROP PROCEDURE IF EXISTS level_getAll;
DELIMITER //
CREATE PROCEDURE level_getAll()
BEGIN
	SELECT 	levelID,
			levelName
	FROM	level_table;
END //
DELIMITER ;


DROP PROCEDURE IF EXISTS shift_add;
DELIMITER //
CREATE PROCEDURE shift_add
(
	IN userIDIN int,
	IN shiftDateIN date
)
BEGIN
	INSERT INTO shift_table (
				userID,
				shiftDate
		)
	VALUES (
				userIDIN,
				shiftDateIN
		);
END //
DELIMITER ;

DROP PROCEDURE IF EXISTS shift_delete;
DELIMITER //
CREATE PROCEDURE shift_delete
(
	IN shiftIDIN int
)
BEGIN
	DELETE FROM shift_table
	WHERE	shiftID = shiftIDIN;
END //
DELIMITER ;

DROP PROCEDURE IF EXISTS shift_edit;
DELIMITER //
CREATE PROCEDURE shift_edit
(
	IN shiftIDIN int,
	IN userIDIN int,
	IN shiftDateIN int
)
BEGIN
	UPDATE 	shift_table
	SET 	userID = userIDIN,
			shiftDate = shiftDateIN
	WHERE	shiftID = shiftIDIN;
END //
DELIMITER ;



-- Add some data
call level_add('admin');
call level_add('Standard');
call level_add('Senior');

call user_add ('Apple', 'Amy', 'Ppl!eta123', 2, 4567);
call user_add ('Berry', 'Bert', 'aSerty456a', 2, 5467);
call user_add ('Carrot', 'Carl', 'asDghj1', 2, 1432);
call user_add ('Donkey', 'Dave', 'sgGgdghj1', 2, 6743);
call user_add ('Emu', 'Ernie', 'tyuIo124', 2, 2456);
call user_add ('Fur', 'Frank', '45frAnk67', 3, 8543);
call user_add ('Goat', 'Graham', 'deDede1', 3, 7832);
call user_add ('timetabler', 'admin', 'organ1sed', 1, 6189);




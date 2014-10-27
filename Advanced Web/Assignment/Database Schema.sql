
-- Drop the tables if they are already in the DB
DROP TABLE IF EXISTS shifts;
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS levels;


-- Create the new tables
CREATE TABLE IF NOT EXISTS levels (
	levelID int NOT NULL AUTO_INCREMENT,
	levelName varchar(100) COLLATE utf8_unicode_ci NOT NULL,
	PRIMARY KEY (levelID),
	INDEX (levelID)
);


CREATE TABLE IF NOT EXISTS users (
	userID int NOT NULL AUTO_INCREMENT,
	surname varchar(200) COLLATE utf8_unicode_ci NOT NULL,
	forename varchar(200) COLLATE utf8_unicode_ci NOT NULL,
	password varchar(100) COLLATE utf8_unicode_ci NOT NULL,
	levelID int NOT NULL,
	staffID int NOT NULL,
	PRIMARY KEY (userID),
	INDEX (userID),
	FOREIGN KEY (levelID) REFERENCES levels (levelID)
);


CREATE TABLE IF NOT EXISTS shifts (
	shiftID bigint NOT NULL AUTO_INCREMENT,
	userID int NOT NULL,
	shiftDate date NOT NULL,
	PRIMARY KEY (shiftID),
	INDEX (shiftID),
	FOREIGN KEY (userID) REFERENCES users (userID)
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
	FROM	users AS u
			INNER JOIN levels AS l ON l.levelID = u.levelID
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
	FROM	users AS u
			INNER JOIN levels AS l ON l.levelID = u.levelID;
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
	INSERT INTO users (
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
	DELETE FROM users
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
	UPDATE users
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
	INSERT INTO levels(
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
	UPDATE 	levels
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
	DELETE FROM levels
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
	FROM	levels
	WHERE	levelID = levelIDIN;
END
DELIMITER ;

DROP PROCEDURE IF EXISTS level_getAll;
DELIMITER //
CREATE PROCEDURE level_getAll()
BEGIN
	SELECT 	levelID,
			levelName
	FROM	levels;
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
	INSERT INTO shifts (
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
	DELETE FROM shifts
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
	UPDATE 	shifts
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

call shift_add ('1', '20141027');
call shift_add ('2', '20141027');
call shift_add ('3', '20141027');
call shift_add ('4', '20141027');
call shift_add ('5', '20141027');
call shift_add ('6', '20141027');
call shift_add ('7', '20141027');
call shift_add ('1', '20141028');
call shift_add ('2', '20141028');
call shift_add ('3', '20141028');
call shift_add ('4', '20141028');


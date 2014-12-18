
-- Drop the tables if they are already in the DB
DROP TABLE IF EXISTS shifts;
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS levels;

-- Create the new tables
CREATE TABLE IF NOT EXISTS levels (
	levelID int NOT NULL AUTO_INCREMENT,
	levelName varchar(100) COLLATE utf8_general_ci NOT NULL,
	neededOnShift int,
	isAdmin bit,
	PRIMARY KEY (levelID),
	INDEX (levelID)
);

ALTER TABLE `levels` ADD UNIQUE(`levelID`);

CREATE TABLE IF NOT EXISTS users (
	userID int NOT NULL AUTO_INCREMENT,
	surname varchar(200) COLLATE utf8_general_ci NOT NULL,
	forename varchar(200) COLLATE utf8_general_ci NOT NULL,
	password varchar(100) COLLATE utf8_general_ci NOT NULL,
	levelID int NOT NULL,
	staffID int NOT NULL,
	emailAddress varchar(100) COLLATE utf8_general_ci NULL,
	phoneNumber varchar(14) COLLATE utf8_general_ci NULL,
	address1 varchar(100) COLLATE utf8_general_ci NULL,
	address2 varchar(100) COLLATE utf8_general_ci NULL,
	city varchar(100) COLLATE utf8_general_ci NULL,
	postcode varchar(9) COLLATE utf8_general_ci NULL,
	PRIMARY KEY (userID),
	INDEX (userID),
	FOREIGN KEY (levelID) REFERENCES levels (levelID)
);


CREATE TABLE IF NOT EXISTS shifts (
	shiftID bigint NOT NULL AUTO_INCREMENT,
	userID int NOT NULL,
	shiftDate date NOT NULL,
	deleted bit NOT NULL,
	userInformed bit NOT NULL,
	PRIMARY KEY (shiftID),
	INDEX (shiftID),
	FOREIGN KEY (userID) REFERENCES users (userID)
);

ALTER table levels DEFAULT COLLATE utf8_general_ci;
ALTER table users DEFAULT COLLATE utf8_general_ci;
ALTER table shifts DEFAULT COLLATE utf8_general_ci;


-- Create the Stored Procedures
-- Delimter changes the ending to // instead of ; Allowing ; to be passed in the sproc

DROP PROCEDURE IF EXISTS user_get;
DELIMITER //
CREATE PROCEDURE user_get
(
	IN id int
)
BEGIN
	SELECT 	u.userID,
			u.surname,
			u.forename,
			l.levelName,
			u.levelID,
			u.staffID,
			u.emailAddress,
			u.phoneNumber,
			u.address1,
			u.address2,
			u.city,
			u.postcode,
			l.isAdmin
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
			INNER JOIN levels AS l ON l.levelID = u.levelID
	WHERE l.isAdmin = 0;
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
				SHA2(CONCAT('zhjbfvh56^%&', CONCAT(passwordIN, staffIDIN)), 256),
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

DROP PROCEDURE IF EXISTS user_messagesConfirm;
DELIMITER //
CREATE PROCEDURE user_messagesConfirm
(
	IN userIDIN int,
	IN deletedIN int
)
BEGIN
	UPDATE shifts
	SET userInformed = 1
	WHERE	userID = userIDIN
	AND userInformed = 0
	AND deleted = deletedIN;

END //
DELIMITER ;


DROP PROCEDURE IF EXISTS user_edit;
DELIMITER //
CREATE PROCEDURE user_edit
(
	IN id int,
	IN passwordIN varchar(100),
	IN forenameIN varchar(100),
	IN surnameIN varchar(100),
	IN emailIN varchar(100),
	IN phoneIN varchar(14),
	IN address1IN varchar(100),
	IN address2IN varchar(100),
	IN cityIN varchar(100),
	IN postcodeIN varchar(9)
)
BEGIN
declare staffIDint int;
 
	SELECT staffID into staffIDint
    FROM users
    WHERE userID = id;

    IF staffIDint is not null
    then
	UPDATE users as u
	SET 	surname = surnameIN,
			forename = forenameIN,
			password = IF(passwordIN = '', u.password, SHA2(CONCAT('zhjbfvh56^%&' , CONCAT(passwordIN, staffIDint)), 256)),
			emailAddress = emailIN,
			phoneNumber = phoneIN,
			address1 = address1IN,
			address2 = address2IN,
			city = cityIN,
			postcode = postcodeIN
	WHERE userID = id;
	end if;
END //
DELIMITER ;


DROP PROCEDURE IF EXISTS level_add;
DELIMITER //
CREATE PROCEDURE level_add
(
	levelNameIN varchar(100),
	levelCoverNeeded int,
	isAdminIN bit
)
BEGIN
	INSERT INTO levels(
			levelName,
			neededOnShift,
			isAdmin
		)
	VALUES (
			levelNameIN,
			levelCoverNeeded,
			isAdminIN
		);
END //
DELIMITER ;

DROP PROCEDURE IF EXISTS level_edit;
DELIMITER //
CREATE PROCEDURE level_edit
(
	IN levelIDIN int,
	IN levelNameIN varchar(100),
	IN isAdminIN bit
)
BEGIN
	UPDATE 	levels
	SET 	levelName = levelNameIN,
			isAdmin = isAdminIN
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


DROP PROCEDURE IF EXISTS add_shift;
DELIMITER //
CREATE PROCEDURE add_shift
(
	IN userIDIN int,
	IN shiftDateIN date,
	IN userInformedIN bit
)
BEGIN
 declare alreadyWorking int;
 
	SELECT shiftID into alreadyWorking
    FROM shifts
    WHERE userID = userIDIN
    and shiftDate = shiftDateIN
    and deleted = 0;
    
    IF alreadyWorking is null then
	INSERT INTO shifts (
				userID,
				shiftDate,
				deleted,
				userInformed
		)
	VALUES (
				userIDIN,
				shiftDateIN,
				0,
				userInformedIN
		);
	SELECT 1 as Success;
    else
    SELECT 0 as Success;
	END IF;
END //
DELIMITER ;

DROP PROCEDURE IF EXISTS shift_countCover;
DELIMITER //
CREATE PROCEDURE shift_countCover
(
	IN shiftIDIN int,
	IN userIDIN int
)
BEGIN
SELECT COUNT(*) as cover
FROM shifts as s
inner join users as u on s.userID = u.userID
inner join users as u1 on u1.userID = userIDIN
inner join shifts as s1 on s1.shiftID = shiftIDIN
where s.shiftDate = s1.shiftDate
and s.deleted = 0
and s.shiftID <> shiftIDIN
and u.levelID = u1.levelID;

call countCoverNeeded(userIDIN);
END //
DELIMITER ;

DROP PROCEDURE IF EXISTS countCoverNeeded;
DELIMITER //
CREATE PROCEDURE countCoverNeeded
(
	IN userIDIN int,
	IN shiftIDIN int
)
BEGIN


SELECT COUNT(*) as cover,
(SELECT l.neededOnShift
FROM levels as l
LEFT join users as u on u.levelid = l.levelid
WHERE u.userid = userIDIN) as coverNeeded
FROM shifts as s
inner join users as u on s.userID = u.userID
inner join users as u1 on u1.userID = userIDIN
inner join shifts as s1 on s1.shiftID = shiftIDIN
where s.shiftDate = s1.shiftDate
and s.deleted = 0
and s.shiftID <> shiftIDIN
and u.levelID = u1.levelID;


END //
DELIMITER ;

DROP PROCEDURE IF EXISTS removeShift_shiftID;
DELIMITER //
CREATE PROCEDURE removeShift_shiftID
(
	IN shiftIDIN int,
	IN userInformedIN bit
)
BEGIN

UPDATE shifts
SET deleted = 1,
	userInformed = userInformedIN
WHERE	shiftID = shiftIDIN;

END //
DELIMITER ;

DROP PROCEDURE IF EXISTS removeShift_userID;
DELIMITER //
CREATE PROCEDURE removeShift_userID
(
	IN shiftDateIN date,
	IN userIDIN int,
	IN userInformedIN bit
)
BEGIN

UPDATE shifts
SET deleted = 1,
	userInformed = userInformedIN
WHERE	userID = userIDIN
AND shiftDate = shiftDateIN;

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

DROP PROCEDURE IF EXISTS user_messages;
DELIMITER //
CREATE PROCEDURE user_messages
(
	IN userIDIN int
)
BEGIN
	SELECT DISTINCT s.shiftDate,
			s.deleted
	FROM	shifts AS s
	WHERE 	s.userID = userIDIN
	AND 	s.userInformed = 0
	ORDER BY s.shiftDate Asc;
END //
DELIMITER ;

DROP PROCEDURE IF EXISTS getEvents_User;
DELIMITER //
CREATE PROCEDURE getEvents_User
(
	shiftDateIN date,
	shiftEndDateIN date,
	userID int
)
BEGIN
	SELECT	COUNT(s.shiftID) AS shiftNumbers,
			l.levelID,
			l.levelName AS levelName,
			s.shiftDate,
			MAX(case
				when s.userID = userID then 1
				else 0
			end) AS onShift,
			MAX(CASE WHEN s.userID = userID then s.shiftID
				else 0
			end) as shiftID,
			u.forename,
			u.userID
	FROM	shifts AS s
	INNER JOIN users AS u on u.userID = s.userID
	INNER JOIN levels AS l on l.levelID = u.levelID
	WHERE	s.shiftDate >= shiftDateIN
	AND		s.shiftDate < shiftEndDateIN
	and s.deleted = 0
	GROUP BY 	l.levelID,
				s.shiftDate Asc
    ORDER BY 	s.shiftDate,
    			l.levelID;
END //
DELIMITER ;

DROP PROCEDURE IF EXISTS countUserShifts_Week;
DELIMITER //
CREATE PROCEDURE countUserShifts_Week
(
	weekStartDateIN date,
	weekEndDateIN date,
	userIDIN int
)
BEGIN
	SELECT COUNT(shiftID) as WeekShifts,
    weekStartDateIN as start,
    weekEndDateIN as end
	FROM SHIFTS
	WHERE userID = userIDIN
	AND shiftDate >= weekStartDateIN
	AND shiftDate <= weekEndDateIN
    AND deleted = 0;
END //
DELIMITER ;

DROP PROCEDURE IF EXISTS getEvents_AllUsers;
DELIMITER //
CREATE PROCEDURE getEvents_AllUsers
(
	shiftDateIN date,
	shiftEndDateIN date
)
BEGIN
	SELECT	u.forename,
			l.levelID,
			l.levelName AS levelName,
			s.shiftDate,
			1 AS onShift,
			s.shiftID as shiftID,
			u.userID
	FROM	shifts AS s
	INNER JOIN users AS u on u.userID = s.userID
	INNER JOIN levels AS l on l.levelID = u.levelID
	WHERE	s.shiftDate >= shiftDateIN
	AND		s.shiftDate < shiftEndDateIN
	AND 	s.deleted = 0
    ORDER BY 	s.shiftDate,
    			l.levelID;
END //
DELIMITER ;

DROP PROCEDURE IF EXISTS countUserShifts;
DELIMITER //
CREATE PROCEDURE countUserShifts
(
	shiftDateIN date,
	shiftEndDateIN date
)
BEGIN
	SELECT 	u.userID,
			u.forename,
			u.surname,
            COUNT(s.shiftID) as shiftsCount
    FROM 	users as u
    LEFT JOIN shifts as s on s.userID = u.userID
		and s.shiftDate >= shiftDateIN
		and s.shiftDate < shiftEndDateIN
		and s.deleted = 0
    inner join levels as l on u.levelID = l.levelID
    where l.isAdmin = 0
    GROUP BY u.userID
    order by u.userID;
END //
DELIMITER ;



DROP PROCEDURE IF EXISTS login;
DELIMITER //
CREATE PROCEDURE login
(
	staffID int,
	initial char(1),
	surname varchar(100),
	password varchar(100)
)
BEGIN
	SELECT	u.forename,
			u.surname,
			u.userID,
			u.staffID,
			u.levelID,
			l.isAdmin
	FROM	users AS u
	INNER JOIN levels AS l on l.levelID = u.levelID
	WHERE	(u.staffID = staffID
			OR (
					initial = LEFT(forename, 1)
					AND
					surname = surname
				)
			)
	AND		BINARY u.password COLLATE utf8_general_ci = BINARY SHA2(CONCAT('zhjbfvh56^%&' , CONCAT(password, u.staffID)),  256) COLLATE utf8_general_ci;
END //
DELIMITER ;


-- Add some data
call level_add('admin', 0, 1);
call level_add('Nurse', 2, 0);
call level_add('Senior', 1, 0);

call user_add ('Apple', 'Amy', 'Ppl!eta123', 2, 4567);
call user_add ('Berry', 'Bert', 'aSerty456a', 2, 5467);
call user_add ('Carrot', 'Carl', 'asDghj1', 2, 1432);
call user_add ('Donkey', 'Dave', 'sgGgdghj1', 2, 6743);
call user_add ('Emu', 'Ernie', 'tyuIo124', 2, 2456);

call user_add ('Fur', 'Frank', '45frAnk67', 3, 8543);
call user_add ('Goat', 'Graham', 'deDede1', 3, 7832);

call user_add ('timetabler', 'admin', 'Organ1sed', 1, 6189);


call add_shift ('2', '20140927', 1);
call add_shift ('1', '20140927', 1);
call add_shift ('3', '20140927', 1);
call add_shift ('4', '20140927', 1);
call add_shift ('7', '20140927', 1);
call add_shift ('6', '20140927', 1);
call add_shift ('5', '20140927', 1);
call add_shift ('7', '20140928', 1);
call add_shift ('6', '20140928', 1);
call add_shift ('2', '20140928', 1);
call add_shift ('1', '20140928', 1);

call add_shift ('2', '20141027', 1);
call add_shift ('1', '20141027', 1);
call add_shift ('3', '20141027', 1);
call add_shift ('4', '20141027', 1);
call add_shift ('7', '20141027', 1);
call add_shift ('6', '20141027', 1);
call add_shift ('5', '20141027', 1);
call add_shift ('7', '20141028', 1);
call add_shift ('6', '20141028', 1);
call add_shift ('2', '20141028', 1);
call add_shift ('1', '20141028', 1);


call add_shift ('2', '20141127', 1);
call add_shift ('1', '20141127', 1);
call add_shift ('3', '20141127', 1);
call add_shift ('4', '20141127', 1);
call add_shift ('7', '20141127', 1);
call add_shift ('6', '20141127', 1);
call add_shift ('5', '20141127', 1);
call add_shift ('7', '20141128', 1);
call add_shift ('6', '20141128', 1);
call add_shift ('2', '20141128', 1);
call add_shift ('1', '20141128', 1);





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


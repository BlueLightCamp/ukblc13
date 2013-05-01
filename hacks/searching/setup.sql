-- Table to hold the people searching...
CREATE  TABLE `blc_db`.`searchprofile` (
  `id` INT NOT NULL ,           -- This is the person's ID number, used in all subsequent transactions.
  `forename` VARCHAR(45) NULL , -- Self explanatory.
  `surname` VARCHAR(45) NULL ,  -- Self explanatory.
  `mobile` VARCHAR(45) NULL ,   -- We will record their mobile number.
  PRIMARY KEY (`id`) );         -- ID is the primary key in this table.


-- Table to hold people's locations...
CREATE TABLE `locations` (
  `id` int(11) DEFAULT NULL,        -- The person's ID number from searchprofile.id.
  `lat` decimal(10,0) DEFAULT NULL, -- Last recorded lattitude.
  `lon` decimal(10,0) DEFAULT NULL,-- Last recorded logitude.
  `recorded` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,  -- Timestamp of when this record was recorded, to allow purging etc.
  PRIMARY KEY (`recorded`),
  KEY `byuser` (`id`)
)

-- Table to hold the text of any chats...
  CREATE  TABLE `blc_db`.`chat` (
  `id` INT NOT NULL ,           -- Simple autoincrmeenting id for each record.
  `chat` VARCHAR(160) NULL ,    -- The text of the message - limit to 160, so it has the same 'feel' as SMS
  `userid` INT NULL ,           -- User's ID from searchprofile.id above.
  `lat` DECIMAL NULL ,          -- Lattitude of user at the point they sent in this chat, or in case of 'outbound' messages, last recorded location of user.
  `lon` DECIMAL NULL ,         -- Longitude of user (same as for lattitude applies).
  PRIMARY KEY (`id`) );         -- Primary key is the rowid.
-- now add an index to be able to get chats for a specific user...
ALTER TABLE `blc_db`.`chat` ADD INDEX `byuser` (`userid` ASC, `id` ASC) ;
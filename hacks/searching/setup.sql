-- Table to hold the searches...
CREATE  TABLE `blc_db`.`searchcase` (
  `id` int(10) unsigned auto_increment PRIMARY KEY not null, -- This is the person's ID number, used in all subsequent transactions.
  `name` VARCHAR(45) NULL ,                                  -- Self explanatory.
  `coordinator` INT NULL ,                                   -- The Userid of the person acting as co-ordinator
  `description` TEXT);                                       -- Self explanatory.

-- Table to hold the people searching...
CREATE  TABLE `blc_db`.`searchprofile` (
  `id` int(10) unsigned auto_increment PRIMARY KEY not null, -- This is the person's ID number, used in all subsequent transactions.
  `searchcase_id` INT ,                                      -- The search this searcher is searching for
  `forename` VARCHAR(45) NULL ,                              -- Self explanatory.
  `surname` VARCHAR(45) NULL ,                               -- Self explanatory.
  `mobile` VARCHAR(45) NULL);                                -- Mobile phone number.


-- Table to hold people's locations...
CREATE TABLE `locations` (
  `searchprofile_id` int(11) DEFAULT NULL,        -- The person's ID number from searchprofile.id.
  `lat` decimal(10,0) DEFAULT NULL, -- Last recorded lattitude.
  `lon` decimal(10,0) DEFAULT NULL,-- Last recorded logitude.
  `recorded` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,  -- Timestamp of when this record was recorded, to allow purging etc.
  PRIMARY KEY (`recorded`),
  KEY `byuser` (`searchprofile_id`)
);

-- Table to hold the text of any chats...
  CREATE  TABLE `blc_db`.`chat` (
  `id` INT NOT NULL ,           -- Simple autoincrmeenting id for each record.
  `chat` VARCHAR(160) NULL ,    -- The text of the message - limit to 160, so it has the same 'feel' as SMS
  `searchprofile_id` INT NULL ,           -- User's ID from searchprofile.id above. Usually same as fromuser field, apart from replies, and broadcasts.
  `lat` DECIMAL NULL ,          -- Lattitude of user at the point they sent in this chat, or in case of 'outbound' messages, last recorded location of user.
  `lon` DECIMAL NULL ,          -- Longitude of user (same as for lattitude applies).
  `fromuser_id` INT NULL,          -- The user creating this statement (Coordinator = -1). See userid above - needed to target replies and enable broadcasts.
  PRIMARY KEY (`id`) );         -- Primary key is the rowid.
-- now add an index to be able to get chats for a specific user...
ALTER TABLE `blc_db`.`chat` ADD INDEX `byuser` (`searchprofile_id` ASC, `id` ASC);


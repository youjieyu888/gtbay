-- CREATE USER 'newuser'@'localhost' IDENTIFIED BY 'password';
CREATE USER IF NOT EXISTS gatechUser@localhost IDENTIFIED BY 'gatech123';
DROP DATABASE IF EXISTS `cs6400_spr18_team071`;
SET default_storage_engine=InnoDB;
SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE DATABASE IF NOT EXISTS cs6400_spr18_team071
DEFAULT CHARACTER SET utf8mb4
DEFAULT COLLATE utf8mb4_unicode_ci;
USE cs6400_spr18_team071;

GRANT SELECT, INSERT, UPDATE, DELETE, FILE ON *.* TO 'gatechUser'@'localhost';
GRANT ALL PRIVILEGES ON `gatechuser`.* TO 'gatechUser'@'localhost';
GRANT ALL PRIVILEGES ON `cs6400_spr18_team071`.* TO 'gatechUser'@'localhost';
FLUSH PRIVILEGES;

drop table if exists RegularUser;
CREATE TABLE RegularUser(
    UserName varchar(20) NOT NULL,
    Password varchar(20) NOT NULL,
    FirstName varchar(50) NOT NULL,
    LastName varchar(50) NOT NULL,
    PRIMARY KEY (UserName)
);
    
drop table if exists Administrator;
CREATE TABLE Administrator(
    UserName varchar(20) NOT NULL,
    Position varchar(100) NOT NULL,
    PRIMARY KEY (UserName)
);
    

drop table if exists Category;
    CREATE TABLE Category (
    CategoryName varchar(50),
    PRIMARY KEY (CategoryName));

drop table if exists `Condition`;
CREATE TABLE `Condition`(
    `Condition` ENUM('New', 'Very Good', 'Good', 'Fair', 'Poor'),
    `Level` int(16) NOT NULL,
    UNIQUE (`LEVEL`),
    PRIMARY KEY (`Condition`)
);


drop table if exists Items;
CREATE TABLE Items(
    ItemID int(16) NOT NULL AUTO_INCREMENT,
    ItemName varchar(50) NOT NULL,
    Description text NOT NULL,    
    `Condition` ENUM('New', 'Very Good', 'Good', 'Fair', 'Poor') NOT NULL,
    UserName varchar(20) NOT NULL,
    Winner varchar(20), 
    Returnable int(1) NOT NULL, 
    StartBid decimal(10,2) NOT NULL CHECK(StartBid>0),
    MinPrice decimal(10,2) NOT NULL CHECK(MinPrice>0),
    GetNowPrice decimal(10,2) CHECK(GetNowPrice>0),
    SalePrice decimal(10,2) CHECK(SalePrice>0), 
    AuctionLen ENUM('24:00:00', '72:00:00', '120:00:00', '168:00:00') NOT NULL,
    AuctionEnd datetime NOT NULL, 
    CategoryName varchar(50) NOT NULL,
    PRIMARY KEY (ItemID),
    CONSTRAINT item_category FOREIGN KEY (CategoryName) REFERENCES Category (CategoryName) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT item_condition FOREIGN KEY (`Condition`) REFERENCES `Condition` (`Condition`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT item_winner FOREIGN KEY (Winner) REFERENCES RegularUser(UserName) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT item_username FOREIGN KEY (UserName) REFERENCES RegularUser(UserName) ON DELETE CASCADE ON UPDATE CASCADE
);
    


drop table if exists Bids;
CREATE TABLE Bids(
    ItemID int(16) NOT NULL,
    UserName varchar(20) NOT NULL,
    Price decimal(10,2) NOT NULL CHECK(Price>0),
    Time datetime NOT NULL,
    PRIMARY KEY (ItemID, Username, Time),
    CONSTRAINT bids_itemid FOREIGN KEY (ItemID) REFERENCES Items(ItemID) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT bids_username FOREIGN KEY (UserName) REFERENCES RegularUser(UserName) ON DELETE CASCADE ON UPDATE CASCADE);


drop table if exists Ratings;
CREATE TABLE Ratings(
    ItemID int(16) NOT NULL,
    UserName varchar(20) NOT NULL,
    Rating int NOT NULL CHECK(Rating>0 && Rating<6),
    Comments text,
    DateTime datetime NOT NULL,
    PRIMARY KEY (ItemID, UserName),
    CONSTRAINT rating_itemid FOREIGN KEY (ItemID) REFERENCES Items(ItemID) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT rating_username FOREIGN KEY (UserName) REFERENCES RegularUser(UserName) ON DELETE CASCADE ON UPDATE CASCADE);

INSERT INTO `Condition` VALUES ('New',5);
INSERT INTO `Condition` VALUES ('Very Good',4);
INSERT INTO `Condition` VALUES ('Good',3);
INSERT INTO `Condition` VALUES ('Fair',2);
INSERT INTO `Condition` VALUES ('Poor',1);

INSERT INTO `Category` VALUES ('Art');
INSERT INTO `Category` VALUES ('Books');
INSERT INTO `Category` VALUES ('Electronics');
INSERT INTO `Category` VALUES ('Home & Garden');
INSERT INTO `Category` VALUES ('Sporting Goods');
INSERT INTO `Category` VALUES ('Toys'); 
INSERT INTO `Category` VALUES ('Other');
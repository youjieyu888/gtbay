-- CREATE USER 'newuser'@'localhost' IDENTIFIED BY 'password';
CREATE USER IF NOT EXISTS gatechUser@localhost IDENTIFIED BY 'gatech123';
DROP DATABASE IF EXISTS `cs6400_sp18_team071`;
SET default_storage_engine=InnoDB;
SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE DATABASE IF NOT EXISTS cs6400_sp18_team071
DEFAULT CHARACTER SET utf8mb4
DEFAULT COLLATE utf8mb4_unicode_ci;
USE cs6400_sp18_team071;

GRANT SELECT, INSERT, UPDATE, DELETE, FILE ON *.* TO 'gatechUser'@'localhost';
GRANT ALL PRIVILEGES ON `gatechuser`.* TO 'gatechUser'@'localhost';
GRANT ALL PRIVILEGES ON `cs6400_sp18_team071`.* TO 'gatechUser'@'localhost';
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

Create View searchview AS
SELECT I.ItemID, I.ItemName, I.Description, Price, B.UserName, I.GetNowPrice, I.AuctionEnd, I.StartBid , I.CategoryName, C.`Level`
FROM Items I LEFT JOIN Bids B ON I.ItemID=B.ItemID LEFT JOIN `Condition` C ON I.`Condition`=C.`Condition` 
Order by ItemID ASC, B.Price DESC;

Create View UL as
SELECT U.UserName, COUNT(ItemID) AS Listed FROM RegularUser U LEFT JOIN Items ON U.UserName=Items.UserName GROUP BY U.UserName;

create view US as 
SELECT U.UserName, COUNT(ItemID) AS Sold FROM RegularUser U LEFT JOIN Items ON U.UserName=Items.UserName WHERE Winner IS NOT NULL GROUP BY U.UserName;

create view UP as SELECT U.UserName, COUNT(ItemID) AS Purchased 
FROM RegularUser U LEFT JOIN Items ON U.UserName=Items.Winner GROUP BY U.UserName;

create view UR as SELECT U.UserName, COUNT(ItemID) AS Rated FROM RegularUser U LEFT JOIN Ratings ON U.UserName=Ratings.UserName GROUP BY UserName;


USE cs6400_sp18_team071;

-- Inserting 18 RegularUsers --

INSERT INTO `RegularUser`(`UserName`, `Password`, `FirstName`, `LastName`) VALUES ('pufan','pufan','Fan', 'Pu');
INSERT INTO `RegularUser`(`UserName`, `Password`, `FirstName`, `LastName`) VALUES ('DavidXue','DavidXue','David', 'Xue');
INSERT INTO `RegularUser`(`UserName`, `Password`, `FirstName`, `LastName`) VALUES ('youjieyu','youjieyu','Jieyu', 'You');
INSERT INTO `RegularUser`(`UserName`, `Password`, `FirstName`, `LastName`) VALUES ('wuqiong','wuqiong','Qiong', 'Wu');
INSERT INTO `RegularUser`(`UserName`, `Password`, `FirstName`, `LastName`) VALUES ('user1', 'pass1', 'Danite', 'Kelor');
INSERT INTO `RegularUser`(`UserName`, `Password`, `FirstName`, `LastName`) VALUES ('user2', 'pass2', 'Dodra', 'Kiney');
INSERT INTO `RegularUser`(`UserName`, `Password`, `FirstName`, `LastName`) VALUES ('user3', 'pass3', 'Peran', 'Bishop');
INSERT INTO `RegularUser`(`UserName`, `Password`, `FirstName`, `LastName`) VALUES ('user4', 'pass4', 'Randy', 'Roran');
INSERT INTO `RegularUser`(`UserName`, `Password`, `FirstName`, `LastName`) VALUES ('user5', 'pass5', 'Ashod', 'Iankel');
INSERT INTO `RegularUser`(`UserName`, `Password`, `FirstName`, `LastName`) VALUES ('user6', 'pass6', 'Cany', 'Achant');
INSERT INTO `RegularUser`(`UserName`, `Password`, `FirstName`, `LastName`) VALUES ('admin1', 'opensesame', 'Riley', 'Fuiss');
INSERT INTO `RegularUser`(`UserName`, `Password`, `FirstName`, `LastName`) VALUES ('admin2', 'opensesayou', 'Tonnis', 'Kinser');



-- Inserting 6 Administrators --

INSERT INTO Administrator Values ('youjieyu', 'admin & Supervisor');
INSERT INTO Administrator Values ('pufan', 'admin & Supervisor');
INSERT INTO Administrator Values ('DavidXue', 'admin & Supervisor');
INSERT INTO Administrator Values ('wuqiong', 'admin & Supervisor');
INSERT INTO Administrator Values ('admin1', 'Technical Support');
INSERT INTO Administrator Values ('admin2', 'Chief Techy');

-- Inserting 7 Items --

INSERT INTO `Items`(`ItemName`, `Description`, `Condition`,`UserName`,`Returnable`,`StartBid`, `MinPrice`, `GetNowPrice`, `AuctionLen`, `AuctionEnd`, `CategoryName`) VALUES ('Garmin GPS', 'This is a great GPS.', 3, 'user1', 0, '50.00', '70.00', '99.00', '72:00:00', '2018-03-31 12:22:00', 'Electronics');
INSERT INTO `Items`(`ItemName`, `Description`, `Condition`,`UserName`,`Returnable`,`StartBid`, `MinPrice`, `GetNowPrice`, `AuctionLen`, `AuctionEnd`, `CategoryName`) VALUES ('Canon Powershot', 'Point and shoot!', 2, 'user1', 0, '40.00', '60.00', '80.00', '72:00:00', '2018-04-01 14:14:00', 'Electronics');
INSERT INTO `Items`(`ItemName`, `Description`, `Condition`,`UserName`,`Returnable`,`StartBid`, `MinPrice`, `GetNowPrice`, `AuctionLen`, `AuctionEnd`, `CategoryName`) VALUES ('Nikon D3', 'New and in box!', 4, 'user2', 0, '1500.00', '1800.00', '2000.00',  '72:00:00', '2018-04-05 9:19:00', 'Electronics');
INSERT INTO `Items`(`ItemName`, `Description`, `Condition`,`UserName`,`Returnable`,`StartBid`, `MinPrice`, `GetNowPrice`, `AuctionLen`, `AuctionEnd`, `CategoryName`) VALUES ('Danish Art Book', 'Delicious Danish Art', 3, 'user3', 1, '10.00', '10.00', '15.00',  '72:00:00', '2018-04-05 15:33:00', 'Art');
INSERT INTO `Items`(`ItemName`, `Description`, `Condition`,`UserName`,`Returnable`,`StartBid`, `MinPrice`, `GetNowPrice`, `AuctionLen`, `AuctionEnd`, `CategoryName`) VALUES ('SQL in 10 Minutes', 'Learn SQL really fast!', 1, 'admin1', 0, '5.00', '10.00', '12.00',  '72:00:00', '2018-04-05 16:48:00', 'Books');
INSERT INTO `Items`(`ItemName`, `Description`, `Condition`,`UserName`,`Returnable`,`StartBid`, `MinPrice`, `GetNowPrice`, `AuctionLen`, `AuctionEnd`, `CategoryName`) VALUES ('SQL in 8 Minutes', 'Learn SQL even faster!', 2, 'admin2', 0, '5.00', '8.00', '10.00',  '72:00:00', '2018-04-08 10:01:00', 'Books');
INSERT INTO `Items`(`ItemName`, `Description`, `Condition`,`UserName`,`Returnable`,`StartBid`, `MinPrice`, `GetNowPrice`, `AuctionLen`, `AuctionEnd`, `CategoryName`) VALUES ('Pull-up Bar', 'Works on any door frame.', 4, 'user6', 1, '20.00', '25.00', '40.00',  '72:00:00', '2018-04-09 22:09:00', 'Sporting Goods');
INSERT INTO `Items`(`ItemName`, `Description`, `Condition`,`UserName`,`Returnable`,`StartBid`, `MinPrice`, `GetNowPrice`, `AuctionLen`, `AuctionEnd`, `CategoryName`) VALUES ('Garmin GPS', 'This is GPS for demo', 'Fair', 'admin2', 0, '25.00', '50.00', '75.00',  '168:00:00', '2018-04-23 03:15:00', 'Electronics');
INSERT INTO `Items`(`ItemName`, `Description`, `Condition`,`UserName`,`Returnable`,`StartBid`, `MinPrice`, `GetNowPrice`, `AuctionLen`, `AuctionEnd`, `CategoryName`) VALUES ('MacBook Pro', 'This is MacBook Pro for demo', 'Very Good', 'user4', 0, '1000', '1500', NULL,  '168:00:00', '2018-04-23 01:01:00', 'Electronics');
INSERT INTO `Items`(`ItemName`, `Description`, `Condition`,`UserName`,`Returnable`,`StartBid`, `MinPrice`, `GetNowPrice`, `AuctionLen`, `AuctionEnd`, `CategoryName`) VALUES ('Microsoft Surface', 'This is Microsoft Surface for demo', 'Good', 'user5', 0, '500', '750', 899,  '168:00:00', '2018-04-23 6:00:00', 'Electronics');


-- Inserting 7 Ratings --
INSERT INTO `Ratings`(`ItemID`, `UserName`, `Rating`, `Comments`, `DateTime`) VALUES (1, 'user2', 5, 'Great GPS!', '2018-03-30 17:00:00');
INSERT INTO `Ratings`(`ItemID`, `UserName`, `Rating`, `Comments`, `DateTime`) VALUES (1, 'user3', 2, 'Not so great GPS!', '2018-03-30 18:00:00');
INSERT INTO `Ratings`(`ItemID`, `UserName`, `Rating`, `Comments`, `DateTime`) VALUES (1, 'user4', 4, 'A favorite of mine.', '2018-03-30 19:00:00');
INSERT INTO `Ratings`(`ItemID`, `UserName`, `Rating`, `Comments`, `DateTime`) VALUES (4, 'user1', 1, 'Go for the Italian stuff instead.', '2018-04-01 16:46:00');
INSERT INTO `Ratings`(`ItemID`, `UserName`, `Rating`, `Comments`, `DateTime`) VALUES (6, 'admin1', 1, 'Not recommended.', '2018-04-06 23:56:00');
INSERT INTO `Ratings`(`ItemID`, `UserName`, `Rating`, `Comments`, `DateTime`) VALUES (6, 'user1', 3, 'This book is okay.', '2018-04-07 13:32:00');
INSERT INTO `Ratings`(`ItemID`, `UserName`, `Rating`, `Comments`, `DateTime`) VALUES (6, 'user2', 5, 'I learned SQL in 8 minutes!', '2018-04-07 14:44:00');
INSERT INTO `Ratings`(`ItemID`, `UserName`, `Rating`, `Comments`, `DateTime`) VALUES (9, 'user5', 5, 'Great for getting OMSCS coursework done.', '2018-04-16 04:44:00');
INSERT INTO `Ratings`(`ItemID`, `UserName`, `Rating`, `Comments`, `DateTime`) VALUES (10, 'user4', 2, 'Looks nice but underpowered.', '2018-04-16 06:44:00');
INSERT INTO `Ratings`(`ItemID`, `UserName`, `Rating`, `Comments`, `DateTime`) VALUES (10, 'user3', 3, NULL ,'2018-04-16 06:46:00');


-- Inserting 7 bid --
INSERT INTO `Bids`(`ItemID`, `UserName`, `Price`, `Time`) VALUES (1, 'user4', 50.00, '2018-03-30 14:53:00');
INSERT INTO `Bids`(`ItemID`, `UserName`, `Price`, `Time`) VALUES (1, 'user5', 55.00, '2018-03-30 16:50:00');
INSERT INTO `Bids`(`ItemID`, `UserName`, `Price`, `Time`) VALUES (1, 'user4', 75.00, '2018-03-30 19:22:00');
INSERT INTO `Bids`(`ItemID`, `UserName`, `Price`, `Time`) VALUES (1, 'user5', 85.00, '2018-03-31 10:00:00');
INSERT INTO `Bids`(`ItemID`, `UserName`, `Price`, `Time`) VALUES (2, 'user6', 80.00, '2018-04-01 13:55:00');
INSERT INTO `Bids`(`ItemID`, `UserName`, `Price`, `Time`) VALUES (3, 'user1', 1500.00, '2018-04-04 8:37:00');
INSERT INTO `Bids`(`ItemID`, `UserName`, `Price`, `Time`) VALUES (3, 'user3', 1501.00, '2018-04-04 9:15:00');
INSERT INTO `Bids`(`ItemID`, `UserName`, `Price`, `Time`) VALUES (3, 'user1', 1795.00, '2018-04-04 12:27:00');
INSERT INTO `Bids`(`ItemID`, `UserName`, `Price`, `Time`) VALUES (7, 'user4', 20.00, '2018-04-08 20:20:00');
INSERT INTO `Bids`(`ItemID`, `UserName`, `Price`, `Time`) VALUES (7, 'user2', 25.00, '2018-04-09 21:15:00');
INSERT INTO `Bids`(`ItemID`, `UserName`, `Price`, `Time`) VALUES (8, 'user4', 30.00, '2018-04-16 03:16:00');
INSERT INTO `Bids`(`ItemID`, `UserName`, `Price`, `Time`) VALUES (8, 'user5', 31.00, '2018-04-16 03:17:00');
INSERT INTO `Bids`(`ItemID`, `UserName`, `Price`, `Time`) VALUES (8, 'user3', 33.00, '2018-04-16 03:18:00');
INSERT INTO `Bids`(`ItemID`, `UserName`, `Price`, `Time`) VALUES (8, 'user4', 40.00, '2018-04-16 03:19:00');
INSERT INTO `Bids`(`ItemID`, `UserName`, `Price`, `Time`) VALUES (8, 'user6', 45.00, '2018-04-16 03:29:00');















